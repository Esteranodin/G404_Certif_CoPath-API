<?php

namespace App\State\Provider; 

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\RatingRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserRatingCollectionProvider implements ProviderInterface
{
    public function __construct(
        private readonly RatingRepository $ratingRepository,
        private readonly Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return [];
        }

        // Retourne SEULEMENT les notes de l'utilisateur connecté
        return $this->ratingRepository->findBy(
            ['user' => $user], 
            ['createdAt' => 'DESC'] // Ordre par date de création
        );
    }
}