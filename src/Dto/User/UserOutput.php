<?php

namespace App\Dto\User;

use App\Entity\User;

class UserOutput
{
    public int $id;
    public string $pseudo;
    public ?string $avatar = null;
    public string $createdAt;
    public int $scenariosCount;
    public int $ratingsCount;
    public int $favoritesCount;
    
    public static function fromEntity(User $user): self
    {
        $output = new self();
        $output->id = $user->getId();
        $output->pseudo = $user->getPseudo() ?? 'Utilisateur';
        $output->avatar = $user->getAvatar();
        $output->createdAt = $user->getCreatedAt()?->format('c') ?? '';
        $output->scenariosCount = $user->getScenarios()->count();
        $output->ratingsCount = $user->getRatings()->count();
        $output->favoritesCount = $user->getFavorites()->count();
        
        return $output;
    }
}