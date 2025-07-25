<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return;
        }

        if ($user->isBan()) {
            throw new CustomUserMessageAccountStatusException(
                'Votre compte a été suspendu. Contactez l\'administrateur.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Vérifications post-authentification si nécessaire
    }
}