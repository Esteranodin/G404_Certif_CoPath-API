<?php

namespace App\Dto\Scenario;

class ScenarioSearchInput
{
    public ?string $search = null;          
    public ?array $campaigns = null;        
    public ?int $authorId = null;          
    public ?float $minRating = null;        
    public ?int $minRatingsCount = null;    
    public ?bool $hasFavorites = null;      
    public ?bool $hasImages = null;        
    // public ?bool $hasMusic = null;         
    public string $sortBy = 'createdAt';    
    public string $sortOrder = 'DESC';      
    public int $page = 1;                 
    public int $itemsPerPage = 20;          
}