<?php

namespace App\Security\Voter;

use App\Entity\Favorite;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FavoriteVoter extends Voter
{
    public const DELETE = 'FAVORITE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE]) && $subject instanceof Favorite;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof User) {
            return false;
        }

        /** @var Favorite $favorite */
        $favorite = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($favorite, $user);
        }

        return false;
    }

    private function canDelete(Favorite $favorite, User $user): bool
    {
        return $favorite->getUser() === $user;
    }
}