<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }


    public function getScenarioRatingStats(int $scenarioId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('AVG(r.score) as average, COUNT(r.id) as count')
            ->where('r.scenario = :scenarioId')
            ->andWhere('r.score IS NOT NULL')
            ->setParameter('scenarioId', $scenarioId);

        $result = $qb->getQuery()->getSingleResult();

        return [
            'average' => $result['average'] ? round((float) $result['average'], 1) : 0,
            'count' => (int) $result['count']
        ];
    }
}
