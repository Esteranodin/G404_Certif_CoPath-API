<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\User\UserMeOutput;
use App\Dto\User\UserPasswordInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof UserPasswordInput) {
            return $data;
        }

        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            throw new \Exception('Utilisateur non connecté');
        }

        if (!$this->passwordHasher->isPasswordValid($currentUser, $data->currentPassword)) {
            throw new BadRequestHttpException('Le mot de passe actuel est incorrect');
        }

        $hashedPassword = $this->passwordHasher->hashPassword($currentUser, $data->newPassword);
        $currentUser->setPassword($hashedPassword);
        $currentUser->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        return UserMeOutput::fromEntity($currentUser);
    }
}