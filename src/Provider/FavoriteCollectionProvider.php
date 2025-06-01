<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\FavoriteRepository;
use Symfony\Bundle\SecurityBundle\Security;

class FavoriteCollectionProvider implements ProviderInterface
{
    public function __construct(
        private FavoriteRepository $favoriteRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return [];
        }

        // Retourne SEULEMENT les favoris de l'utilisateur connecté
        return $this->favoriteRepository->findBy(['user' => $user]);
    }
}