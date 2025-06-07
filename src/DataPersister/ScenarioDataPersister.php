<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Scenario;
use App\Service\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioDataPersister extends AbstractDataPersister implements ProcessorInterface
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        private readonly ScenarioService $scenarioService,
    ) {
        parent::__construct($entityManager, $security);
    }

    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Scenario) {
            return;
        }

        if ($operation instanceof Post) {
            $this->scenarioService->createScenario($data);
        }
    }
}
