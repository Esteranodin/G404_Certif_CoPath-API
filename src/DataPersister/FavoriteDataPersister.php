<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class FavoriteDataPersister extends AbstractDataPersister implements ProcessorInterface
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        private readonly FavoriteRepository $favoriteRepository
    ) {
        parent::__construct($entityManager, $security);
    }

    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Favorite || !$operation instanceof Post) {
            return;
        }

        $user = $this->security->getUser();
        $scenario = $data->getScenario();

        $existingFavorite = $this->favoriteRepository->findOneBy([
            'user' => $user,
            'scenario' => $scenario
        ]);

        if ($existingFavorite) {
            $this->entityManager->remove($existingFavorite);
            $this->entityManager->flush();
            
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(
                200, 
                json_encode([
                    'message' => 'Favori supprimé',
                    'action' => 'removed',
                    'scenario' => $scenario->getId()
                ])
            );
        }
    }
}