<?php

namespace App\DataFixtures;

use App\Entity\Favorite;
use App\Entity\Scenario;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ScenarioFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $scenarioCount = $manager->getRepository(Scenario::class)->count([]);
        $existingFavorites = [];
        $created = 0;
        $maxAttempts = 100;
        $attempts = 0;

        while ($created < 30 && $attempts < $maxAttempts) {
            $userIndex = $faker->numberBetween(0, 9);
            $scenarioIndex = $faker->numberBetween(0, $scenarioCount - 1);
            $key = $userIndex . '_' . $scenarioIndex;

            $attempts++;

            if (in_array($key, $existingFavorites)) {
                continue;
            }
            $existingFavorites[] = $key;

            $favorite = new Favorite();

            $favorite->setUser($this->getReference('user_' . $userIndex, User::class));
            $favorite->setScenario($this->getReference('scenario_' . $scenarioIndex, Scenario::class));

            $favorite->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));
            $favorite->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));

            $manager->persist($favorite);
            $this->addReference('favorite_' . $created, $favorite);

             $created++;
        }

        $manager->flush();
    }
}
