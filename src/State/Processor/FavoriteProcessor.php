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

        // ✅ Extraire l'ID du scénario depuis les données brutes
        $scenarioId = null;
        
        // Gérer le format { scenario: "/api/scenarios/123" }
        if (is_array($data) && isset($data['scenario'])) {
            $scenarioIri = $data['scenario'];
            if (preg_match('/\/scenarios\/(\d+)$/', $scenarioIri, $matches)) {
                $scenarioId = (int) $matches[1];
            }
        }
        // Gérer si c'est déjà une entité Favorite
        elseif ($data instanceof Favorite && $data->getScenario()) {
            $scenarioId = $data->getScenario()->getId();
        }

        if (!$scenarioId) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Scenario ID missing or invalid');
        }

        // ✅ Récupérer le scénario
        $scenario = $this->scenarioRepository->find($scenarioId);
        if (!$scenario) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Scenario not found');
        }

        // ✅ Vérifier si déjà en favoris (TOGGLE)
        $existingFavorite = $this->favoriteRepository->findOneBy([
            'user' => $user,
            'scenario' => $scenario
        ]);

        if ($existingFavorite) {
            // ✅ Supprimer si existe déjà
            $this->entityManager->remove($existingFavorite);
            $this->entityManager->flush();
            
            return [
                'message' => 'Favori supprimé',
                'action' => 'removed',
                'scenarioId' => $scenario->getId(),
                'isFavorite' => false
            ];
        } else {
            // ✅ Ajouter si n'existe pas
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setScenario($scenario);
            $favorite->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($favorite);
            $this->entityManager->flush();

            return [
                'message' => 'Favori ajouté',
                'action' => 'added',
                'scenarioId' => $scenario->getId(),
                'isFavorite' => true,
                'favorite' => $favorite
            ];
        }
    }
}