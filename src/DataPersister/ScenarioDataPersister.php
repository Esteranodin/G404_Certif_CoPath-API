<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Scenario;
use App\Service\ScenarioService;


class ScenarioDataPersister extends AbstractDataPersister implements ProcessorInterface
{
    protected function processSpecific(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Scenario) {
            return;
        }
    }

    public function __construct(
        private readonly ScenarioService $scenarioService,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Scenario
    {
        if ($data instanceof Scenario && $operation instanceof Post) {
            return $this->scenarioService->createScenario($data);
        }

        if ($data instanceof Scenario) {
            $data->setUpdatedAt(new \DateTimeImmutable());
        }

        return $data;
    }
}
