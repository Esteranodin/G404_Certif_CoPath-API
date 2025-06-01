<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin->setEmail('pamelarose@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'pamelarose'));
        $admin->setPseudo('SuperAdmin');
        $admin->setAvatar('/uploads/user-avatars/avatar1.jpg');
        $admin->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d H:i:s')));
        $admin->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d H:i:s')));
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        // Users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setPseudo($faker->userName());
            $user->setAvatar($faker->imageUrl(128, 128, 'people', true, 'avatar'));
            $user->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));
            $user->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s')));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
