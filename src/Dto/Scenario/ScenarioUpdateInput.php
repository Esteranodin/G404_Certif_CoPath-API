<?php

namespace App\Dto\Scenario;

use Symfony\Component\Validator\Constraints as Assert;

class ScenarioUpdateInput
{
    #[Assert\Length(
        min: 3, 
        max: 255, 
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
    )]
    public ?string $title = null;

    #[Assert\Length(
        min: 10,
        minMessage: 'Le contenu doit faire au moins {{ limit }} caractères'
    )]
    public ?string $content = null;

    #[Assert\Type('array')]
    public ?array $campaignIds = null;  
}