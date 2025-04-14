<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Interfaces\HasCreatedAtInterface;
use App\Entity\Interfaces\HasUpdatedAtInterface;
use App\Entity\Interfaces\HasUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

abstract class AbstractDataPersister implements ProcessorInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Dates & users
        if ($data instanceof HasUserInterface && $operation instanceof Post) {
            $data->setUser($this->security->getUser());
        }
        
        if ($data instanceof HasCreatedAtInterface && $operation instanceof Post) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        
        if ($data instanceof HasUpdatedAtInterface && ($operation instanceof Post || $operation instanceof Patch)) {
            $data->setUpdatedAt(new \DateTimeImmutable());
        }
        
        // Logique spécifique dans les classes enfants
        $this->processSpecific($data, $operation, $uriVariables, $context);
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        
        return $data;
    }
    
    abstract protected function processSpecific(mixed $data, Operation $operation, array $uriVariables, array $context): void;
}