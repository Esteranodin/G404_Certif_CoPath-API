<?php

namespace App\Dto\Scenario;

use App\Entity\Scenario;

class ScenarioListOutput
{
    public int $id;
    public string $title;
    public string $content;
    public float $averageRating;
    public int $ratingsCount;
    public int $favoritesCount;
    public \DateTimeInterface $createdAt;  
    public \DateTimeInterface $updatedAt; 

    public array $author;
    public array $images;
    public array $campaigns;

    public static function fromEntity(Scenario $scenario): self
    {
        $output = new self();
        $output->id = $scenario->getId();
        $output->title = $scenario->getTitle();
        $output->content = $scenario->getContent();
        $output->createdAt = $scenario->getCreatedAt();
        $output->updatedAt = $scenario->getUpdatedAt();

        $user = $scenario->getUser();
        $output->author = [
            'id' => $user->getId(),
            'name' => $user->getPseudo() ?? 'Utilisateur'
        ];

        // Ratings
        $ratings = $scenario->getRatings();
        if ($ratings->count() > 0) {
            $total = 0;
            foreach ($ratings as $rating) {
                $total += $rating->getScore();
            }
            $output->averageRating = $total / $ratings->count();
        } else {
            $output->averageRating = 0.0;
        }
        $output->ratingsCount = $ratings->count();

        // Favoris
        $output->favoritesCount = $scenario->getFavorites()->count();

        // ✅ RÉCUPÈRE LES VRAIES IMAGES
        $images = $scenario->getImg();
        $output->images = [];
        
        foreach ($images as $image) {
            $output->images[] = [
                'id' => $image->getId(),
                'path' => $image->getImgPath(),
                'alt' => $image->getImgAlt()
            ];
        }

        // Campaigns
        $output->campaigns = [];
        foreach ($scenario->getCampaign() as $campaign) {
            $output->campaigns[] = [
                'id' => $campaign->getId(),
                'name' => $campaign->getName()
            ];
        }

        return $output;
    }
}
