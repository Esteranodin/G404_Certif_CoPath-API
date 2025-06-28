<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use App\Repository\ScenarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class FavoriteProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly FavoriteRepository $favoriteRepository,
        private readonly ScenarioRepository $scenarioRepository
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('User not authenticated');
        }

        if ($operation->getName() === '_api_/favorites/{id}_delete') {
            if ($data instanceof Favorite) {
                $this->entityManager->remove($data);
                $this->entityManager->flush();
                return null;
            }
        }

        $scenarioId = null;
        
        if ($data instanceof Favorite && $data->getScenario()) {
            $scenarioId = $data->getScenario()->getId();
        }

        if (!$scenarioId) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Scenario ID missing or invalid');
        }

        $scenario = $this->scenarioRepository->find($scenarioId);
        if (!$scenario) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Scenario not found');
        }

        $existingFavorite = $this->favoriteRepository->findOneBy([
            'user' => $user,
            'scenario' => $scenario
        ]);

        if ($existingFavorite) {
            $this->entityManager->remove($existingFavorite);
            $this->entityManager->flush();
            return null;
        } else {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setScenario($scenario);
            $favorite->setCreatedAt(new \DateTimeImmutable());
            $favorite->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($favorite);
            $this->entityManager->flush();

            return $favorite; 
        }
    }
}