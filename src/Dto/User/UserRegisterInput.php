<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterInput
{
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email doit être valide')]
    public string $email;

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères'
    )]
    public string $plainPassword;

    #[Assert\NotBlank(message: 'Le pseudo est obligatoire')]
    #[Assert\Length(
        min: 3, 
        max: 17, 
        minMessage: 'Le pseudo doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le pseudo ne peut pas dépasser {{ limit }} caractères'
    )]
    public string $pseudo;

    #[Assert\Url(message: 'L\'avatar doit être une URL valide')]
    public ?string $avatar = null;
}