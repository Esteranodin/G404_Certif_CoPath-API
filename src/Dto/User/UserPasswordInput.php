<?php
// src/Dto/User/UserPasswordInput.php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordInput
{
    #[Assert\NotBlank(message: 'Le mot de passe actuel est obligatoire')]
    public string $currentPassword;

    #[Assert\NotBlank(message: 'Le nouveau mot de passe est obligatoire')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Le nouveau mot de passe doit faire au moins {{ limit }} caractères'
    )]
    public string $newPassword;

    #[Assert\NotBlank(message: 'La confirmation est obligatoire')]
    #[Assert\EqualTo(
        propertyPath: 'newPassword', 
        message: 'Les mots de passe ne correspondent pas'
    )]
    public string $confirmPassword;
}