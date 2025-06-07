<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RatingDataPersister extends AbstractDataPersister implements ProcessorInterface
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
        if (!$data instanceof Rating || !$operation instanceof Post) {
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

            throw new \Symfony\Component\HttpKernel\Exception\ConflictHttpException(
                'Note mise à jour avec succès'
            );
        }
    }
}