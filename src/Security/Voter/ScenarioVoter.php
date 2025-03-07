<?php

namespace App\Security\Voter;

use App\Entity\Scenario;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ScenarioVoter extends Voter{
    public const EDIT = 'SCENARIO_EDIT';
    public const DELETE = 'SCENARIO_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Scenario;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Scenario $scenario */
        $scenario = $subject;

        return match($attribute) {
            self::EDIT, self::DELETE => $this->isOwner($scenario, $user),
            default => false,
        };
    }

    private function isOwner(Scenario $scenario, User $user): bool
    {
        return $scenario->getUser() === $user;
    }
}
