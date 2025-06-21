<?php

namespace App\Dto\Scenario;

use App\Entity\Scenario;

class ScenarioDetailOutput
{
    public int $id;
    public string $title;
    public string $content;
    public float $averageRating;
    public int $ratingsCount;
    public int $favoritesCount;
    public string $createdAt;
    public string $updatedAt;
    public array $author;
    public array $images = [];
    public array $music = [];
    public array $campaigns = [];
    // Pour user connecté
    public ?int $myRating = null;
    public bool $isFavorite = false;

    public static function fromEntity(Scenario $scenario, $currentUser = null): self
    {
        $output = new self();
        $output->id = $scenario->getId();
        $output->title = $scenario->getTitle();
        $output->content = $scenario->getContent() ?? '';
        $output->averageRating = $scenario->getAverageRating();
        $output->ratingsCount = $scenario->getRatingsCount();
        $output->favoritesCount = $scenario->getFavoritesCount();
        $output->createdAt = $scenario->getCreatedAt()?->format('c') ?? '';
        $output->updatedAt = $scenario->getUpdatedAt()?->format('c') ?? '';

        $user = $scenario->getUser();
        $output->author = [
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo() ?? 'Utilisateur',
            'avatar' => $user->getAvatar()
        ];

        $output->images = $scenario->getImg()->map(function ($img) {
            return [
                'id' => $img->getId(),
                'path' => $img->getImgPath(),      
                'alt' => $img->getImgAlt()        
            ];
        })->toArray();

        $output->music = $scenario->getMusic()->map(function ($music) {
            return [
                'id' => $music->getId(),
                'path' => $music->getMusicPath()  
            ];
        })->toArray();

        $output->campaigns = $scenario->getCampaign()->map(function ($campaign) {
            return [
                'id' => $campaign->getId(),
                'name' => $campaign->getName(),
            ];
        })->toArray();

        if ($currentUser) {
            $myRating = $scenario->getRatings()->filter(
                fn($rating) => $rating->getUser() === $currentUser
            )->first();
            $output->myRating = $myRating ? $myRating->getScore() : null;

            $output->isFavorite = $scenario->getFavorites()->exists(
                fn($key, $favorite) => $favorite->getUser() === $currentUser
            );
        }

        return $output;
    }
}
