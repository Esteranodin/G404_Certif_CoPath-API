<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RatingProcessor extends AbstractProcessor implements ProcessorInterface
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        private readonly RatingRepository $ratingRepository
    ) {
        parent::__construct($entityManager, $security);
    }

    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Rating) {
            return;
        }

        $user = $this->security->getUser();
        $scenario = $data->getScenario();

        $existingRating = $this->ratingRepository->findOneBy([
            'user' => $user,
            'scenario' => $scenario
        ]);

        if ($existingRating) {
            $existingRating->setScore($data->getScore());
            $existingRating->setUpdatedAt(new \DateTimeImmutable());
            $data = $existingRating; 
        }

        $this->updateScenarioRatingStats($scenario);
    }

    private function updateScenarioRatingStats($scenario): void
    {
        $stats = $this->ratingRepository->getScenarioRatingStats($scenario->getId());

        $scenario->setAverageRating($stats['average']);
        $scenario->setRatingsCount($stats['count']);

        $this->entityManager->persist($scenario);
    }
}