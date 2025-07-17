<?php

namespace App\Repository;

use App\Dto\Scenario\ScenarioSearchInput;
use App\Entity\Scenario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scenario>
 */
class ScenarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scenario::class);
    }

    /**
     * @return Scenario[] array d'objects Scenario
     */
    public function findByTitle(?string $title = null): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($title) {
            $qb->andWhere('s.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

        return $qb->orderBy('s.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findBySearchCriteria(ScenarioSearchInput $input): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.user', 'u')
            ->leftJoin('s.campaign', 'c')
            ->leftJoin('s.img', 'i')
            ->leftJoin('s.music', 'm')
            ->addSelect('u', 'c', 'i', 'm');

        if ($input->search) {
            $qb->andWhere('s.title LIKE :search OR s.content LIKE :search')
                ->setParameter('search', '%' . $input->search . '%');
        }

        if ($input->campaigns) {
            $qb->andWhere('c.id IN (:campaigns)')
                ->setParameter('campaigns', $input->campaigns);
        }

        if ($input->authorId) {
            $qb->andWhere('u.id = :authorId')
                ->setParameter('authorId', $input->authorId);
        }

        if ($input->minRating) {
            $qb->andWhere('s.averageRating >= :minRating')
                ->setParameter('minRating', $input->minRating);
        }

        if ($input->minRatingsCount) {
            $qb->andWhere('s.ratingsCount >= :minRatingsCount')
                ->setParameter('minRatingsCount', $input->minRatingsCount);
        }

        if ($input->hasImages) {
            $qb->andWhere('i.id IS NOT NULL');
        }

        // if ($input->hasMusic) {
        //     $qb->andWhere('m.id IS NOT NULL');
        // }

        $qb->orderBy('s.' . $input->sortBy, $input->sortOrder);

        $qb->setFirstResult(($input->page - 1) * $input->itemsPerPage)
            ->setMaxResults($input->itemsPerPage);

        return $qb->getQuery()->getResult();
    }

    public function countBySearchCriteria(ScenarioSearchInput $input): int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s.id)')
            ->leftJoin('s.user', 'u')
            ->leftJoin('s.campaign', 'c')
            ->leftJoin('s.img', 'i')
            ->leftJoin('s.music', 'm');

        if ($input->search) {
            $qb->andWhere('s.title LIKE :search OR s.content LIKE :search')
                ->setParameter('search', '%' . $input->search . '%');
        }

        if ($input->campaigns) {
            $qb->andWhere('c.id IN (:campaigns)')
                ->setParameter('campaigns', $input->campaigns);
        }

        if ($input->authorId) {
            $qb->andWhere('u.id = :authorId')
                ->setParameter('authorId', $input->authorId);
        }

        if ($input->minRating) {
            $qb->andWhere('s.averageRating >= :minRating')
                ->setParameter('minRating', $input->minRating);
        }

        if ($input->minRatingsCount) {
            $qb->andWhere('s.ratingsCount >= :minRatingsCount')
                ->setParameter('minRatingsCount', $input->minRatingsCount);
        }

        if ($input->hasImages) {
            $qb->andWhere('i.id IS NOT NULL');
        }

        // if ($input->hasMusic) {
        //     $qb->andWhere('m.id IS NOT NULL');
        // }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
