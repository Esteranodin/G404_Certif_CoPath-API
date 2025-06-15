<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Scenario\ScenarioListOutput;
use App\Dto\Scenario\ScenarioSearchInput;
use App\Repository\ScenarioRepository;

class ScenarioSearchProvider implements ProviderInterface
{
    public function __construct(
        private readonly ScenarioRepository $scenarioRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $searchInput = new ScenarioSearchInput();
        
        $request = $context['request'] ?? null;
        if ($request) {
            $searchInput->search = $request->query->get('search');
            $searchInput->campaigns = $request->query->get('campaigns');
            $searchInput->authorId = $request->query->get('authorId');
            $searchInput->minRating = $request->query->get('minRating');
            $searchInput->minRatingsCount = $request->query->get('minRatingsCount');
            $searchInput->hasFavorites = $request->query->getBoolean('hasFavorites');
            $searchInput->hasImages = $request->query->getBoolean('hasImages');
            $searchInput->sortBy = $request->query->get('sortBy', 'createdAt');
            $searchInput->sortOrder = $request->query->get('sortOrder', 'DESC');
            $searchInput->page = (int) $request->query->get('page', 1);
            $searchInput->itemsPerPage = (int) $request->query->get('itemsPerPage', 20);
        }

        $scenarios = $this->scenarioRepository->findBySearchCriteria($searchInput);
        $total = $this->scenarioRepository->countBySearchCriteria($searchInput);

        // Conversion en DTO
        $results = array_map(
            fn($scenario) => ScenarioListOutput::fromEntity($scenario),
            $scenarios
        );

        return [
            'member' => $results,
            'totalItems' => $total,
            'view' => [
                'first' => "?page=1",
                'last' => "?page=" . ceil($total / $searchInput->itemsPerPage),
                'previous' => $searchInput->page > 1 ? "?page=" . ($searchInput->page - 1) : null,
                'next' => $searchInput->page < ceil($total / $searchInput->itemsPerPage) ? "?page=" . ($searchInput->page + 1) : null,
            ]
        ];
    }
}