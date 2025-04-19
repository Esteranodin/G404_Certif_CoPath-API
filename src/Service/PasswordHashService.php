<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHashService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashUserPassword(User $user): void
    {
        // Ne faire le hashage que si un mot de passe en clair est fourni
        if ($plainPassword = $user->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);
            // Effacer le mot de passe en clair pour des raisons de sécurité
            $user->setPlainPassword(null);
        }
    }
}