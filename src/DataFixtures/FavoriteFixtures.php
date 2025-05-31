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

        for ($i = 0; $i < 30; $i++) {
            $userIndex = $faker->numberBetween(0, 9);
            $scenarioIndex = $faker->numberBetween(0, $scenarioCount - 1);
            
            $key = $userIndex . '_' . $scenarioIndex;
            if (in_array($key, $existingFavorites)) {
                continue;
            }
            $existingFavorites[] = $key;

            $favorite = new Favorite();
            
            $user = $this->getReference('user_' . $userIndex, User::class);
            $favorite->setUser($user);
            
            $scenario = $this->getReference('scenario_' . $scenarioIndex, Scenario::class);
            $favorite->setScenario($scenario);
            
            $favorite->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));

            $manager->persist($favorite);
            $this->addReference('favorite_' . $i, $favorite);
        }

        $manager->flush();
    }
}