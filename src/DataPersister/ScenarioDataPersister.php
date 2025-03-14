<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Scenario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioDataPersister implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Scenario
    {
        if ($data instanceof Scenario && $operation instanceof Post) {
            $data->setUser($this->security->getUser());
        }

       
// data = scenario masi il faut passer par la requete directement avant hydratation objet scenario mais plutot gerer moi meme creation et chemin et persiste

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}