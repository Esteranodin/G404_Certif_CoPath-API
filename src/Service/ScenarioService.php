<?php

namespace App\Service;

use App\Entity\Scenario;
use App\Entity\ImgScenario;
use App\Entity\Music;
use App\Repository\ScenarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly Security $security
    ) {}
    
    public function createScenario(Scenario $scenario): Scenario
    {
        $scenario->setUser($this->security->getUser());
        $scenario->setCreatedAt(new \DateTimeImmutable());
        $scenario->setUpdatedAt(new \DateTimeImmutable());
        
        $this->entityManager->persist($scenario);
        $this->entityManager->flush();
        
        return $scenario;
    }
    
    public function addImageToScenario(Scenario $scenario, ImgScenario $image): void
    {
        $image->setScenario($scenario);
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }
    
    public function addMusicToScenario(Scenario $scenario, Music $music): void
    {
        $music->setScenario($scenario);
        $this->entityManager->persist($music);
        $this->entityManager->flush();
    }
    
    public function getUserScenarios(): array
    {
        $user = $this->security->getUser();
        return $this->scenarioRepository->findBy(['user' => $user]);
    }
}