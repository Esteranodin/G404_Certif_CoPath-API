<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\User\UserMeOutput;
use App\Dto\User\UserRegisterInput;
use App\Dto\User\UserUpdateInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof Post) {
            return $this->handleRegistration($data);
        }
        
        if ($operation instanceof Patch) {
            return $this->handleUpdate($data);
        }

        return $data;
    }

    private function handleRegistration(UserRegisterInput $input): UserMeOutput
    {
        $user = new User();
        $user->setEmail($input->email);
        $user->setPseudo($input->pseudo);
        
        if ($input->avatar !== null) {
            $user->setAvatar($input->avatar);
        }
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $input->plainPassword);
        $user->setPassword($hashedPassword);
        
        $user->setRoles(['ROLE_USER']);
        
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserMeOutput::fromEntity($user);
    }

    private function handleUpdate(UserUpdateInput $input): UserMeOutput
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            throw new \Exception('Utilisateur non connecté ou type invalide');
        }
       
        if ($input->pseudo !== null) {
            $currentUser->setPseudo($input->pseudo);
        }
        
        if ($input->avatar !== null) {
            $currentUser->setAvatar($input->avatar);
        }
        
        if ($input->email !== null) {
            $currentUser->setEmail($input->email);
        }
        
        $currentUser->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        return UserMeOutput::fromEntity($currentUser);
    }
}
