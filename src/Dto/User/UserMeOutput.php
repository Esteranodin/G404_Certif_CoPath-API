<?php

namespace App\Dto\User;

use App\Entity\User;

class UserMeOutput
{
    public int $id;
    public string $email;       
    public string $pseudo;
    public ?string $avatar = null;
    public array $roles;       
    public string $createdAt;
    public int $scenariosCount;
    public int $ratingsCount;
    public int $favoritesCount;

    public static function fromEntity(User $user): self
    {
        $output = new self();
        $output->id = $user->getId();
        $output->email = $user->getEmail();
        $output->pseudo = $user->getPseudo() ?? 'Utilisateur';
        $output->avatar = $user->getAvatar();
        $output->roles = $user->getRoles();
        $output->createdAt = $user->getCreatedAt()?->format('c') ?? '';
        $output->scenariosCount = $user->getScenarios()->count();
        $output->ratingsCount = $user->getRatings()->count();
        $output->favoritesCount = $user->getFavorites()->count();
        
        return $output;
    }
}