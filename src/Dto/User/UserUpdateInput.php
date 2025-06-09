<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateInput
{
    #[Assert\Length(
        min: 3, 
        max: 17, 
        minMessage: 'Le pseudo doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le pseudo ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $pseudo = null;

    #[Assert\Url(message: 'L\'avatar doit être une URL valide')]
    public ?string $avatar = null;

    #[Assert\Email(message: 'L\'email doit être valide')]
    public ?string $email = null;
}