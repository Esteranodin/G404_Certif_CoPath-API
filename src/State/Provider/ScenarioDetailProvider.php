<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Scenario\ScenarioDetailOutput;
use App\Repository\ScenarioRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioDetailProvider implements ProviderInterface
{
    public function __construct(
        private readonly ScenarioRepository $scenarioRepository,
        private readonly Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $scenarioId = $uriVariables['id'] ?? null;
        
        if (!$scenarioId) {
            return null;
        }

        $scenario = $this->scenarioRepository->find($scenarioId);
        
        if (!$scenario) {
            return null;
        }

        $currentUser = $this->security->getUser();

        return ScenarioDetailOutput::fromEntity($scenario, $currentUser);
    }
}