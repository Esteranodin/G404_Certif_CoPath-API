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
    public string $createdAt;
    public string $updatedAt;

    public array $author;
    public array $images;
    public array $campaigns;

    public static function fromEntity(Scenario $scenario): self
    {
        error_log('🔍 DTO - Converting scenario ID: ' . $scenario->getId());

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
            'name' => $user->getPseudo() ?? 'Utilisateur'
        ];

        $output->images = [];
        foreach ($scenario->getImg() as $image) {
            $output->images[] = [
                'id' => $image->getId(),
                'url' => $image->getImgPath()
            ];
        }

        $output->campaigns = [];
        foreach ($scenario->getCampaign() as $campaign) {
            $output->campaigns[] = [
                'id' => $campaign->getId(),
                'name' => $campaign->getName()
            ];
        }

        error_log('🔍 DTO - Converted: ' . $output->title);
        return $output;
    }
}
