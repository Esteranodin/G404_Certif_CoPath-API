<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Campaign;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CampaignProcessor extends AbstractProcessor implements ProcessorInterface
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        parent::__construct($entityManager, $security);
    }

    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Campaign) {
            return;
        }
    }
}
