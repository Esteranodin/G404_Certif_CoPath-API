<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
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
        // Vérifier si l'entité a la méthode setUser (BlamableTrait)
        if (method_exists($data, 'setUser') && $operation instanceof Post) {
            $data->setUser($this->security->getUser());
        }
        
        // Vérifier si l'entité a la méthode setCreatedAt (TimestampableTrait)
        if (method_exists($data, 'setCreatedAt') && $operation instanceof Post) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        
        // Vérifier si l'entité a la méthode setUpdatedAt (TimestampableTrait)
        if (method_exists($data, 'setUpdatedAt') && ($operation instanceof Post || $operation instanceof Patch)) {
            $data->setUpdatedAt(new \DateTimeImmutable());
        }
        
        $this->processSpecific($data, $operation, $uriVariables, $context);
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        
        return $data;
    }
    
    abstract protected function processSpecific(mixed $data, Operation $operation, array $uriVariables, array $context): void;
}