<?php

namespace App\Security\Voter;

use App\Entity\Campaign;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CampaignVoter extends Voter{
    public const EDIT = 'CAMPAIGN_EDIT';
    public const DELETE = 'CAMPAIGN_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Campaign;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Campaign $campaign */
        $campaign = $subject;

        return match($attribute) {
            self::EDIT, self::DELETE => $this->isOwner($campaign, $user),
            default => false,
        };
    }

    private function isOwner(Campaign $campaign, User $user): bool
    {
        return $campaign->getUser() === $user;
    }
}
