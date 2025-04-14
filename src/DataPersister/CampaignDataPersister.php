<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Campaign;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CampaignDataPersister extends AbstractDataPersister implements ProcessorInterface
{

    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Campaign) {
            return;
        }
    }

    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Campaign
    {

        if ($data instanceof Campaign) {
            // Associer user connecté lors de la création
            if ($operation instanceof Post) {
                $data->setUser($this->security->getUser());
                $data->setCreatedAt(new \DateTimeImmutable());
            }

            // Maj date de modification
            if ($operation instanceof Patch || $operation instanceof Post) {
                $data->setUpdatedAt(new \DateTimeImmutable());
            }
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
