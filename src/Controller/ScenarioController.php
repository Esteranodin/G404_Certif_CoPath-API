<?php

namespace App\Controller;

use App\Repository\ScenarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
final class ScenarioController extends AbstractController
{
    public function __construct(
        private readonly ScenarioRepository $scenarioRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('/scenarios/search', name: 'scenarios_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        // Endpoint de recherche personnalisé
        $title = $request->query->get('title');

        $scenarios = $this->scenarioRepository->findByTitle($title);

        return $this->json(['data' => $scenarios], 200, [], ['groups' => 'scenario:read']);
    }

    #[Route('/scenarios/user', name: 'scenarios_user', methods: ['GET'])]
    public function getUserScenarios(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non connecté'], 401);
        }

        $scenarios = $this->scenarioRepository->findBy(['user' => $user]);

        return $this->json(['data' => $scenarios], 200, [], ['groups' => 'scenario:read']);
    }
}
