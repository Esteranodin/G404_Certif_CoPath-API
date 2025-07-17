<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

abstract class AbstractProcessor implements ProcessorInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (method_exists($data, 'setUser') && $operation instanceof Post) {
            $currentUser = $this->security->getUser();
            if ($currentUser) {
                $data->setUser($currentUser);
            }
        }
        
        if (method_exists($data, 'setCreatedAt') && $operation instanceof Post) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        
        if (method_exists($data, 'setUpdatedAt')) {
            $data->setUpdatedAt(new \DateTimeImmutable());
        }
        
        $this->processSpecific($data, $operation, $uriVariables, $context);
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        
        return $data;
    }
    
    abstract protected function processSpecific(mixed $data, Operation $operation, array $uriVariables, array $context): void;
}