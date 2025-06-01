<?php

namespace App\DataFixtures;

use App\Entity\Rating;
use App\Entity\Scenario;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RatingFixtures extends Fixture implements DependentFixtureInterface
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
        $existingRatings = []; 
        $created = 0;
        $maxAttempts = 150;
        $attempts = 0;

        while ($created < 50 && $attempts < $maxAttempts) {
            $userIndex = $faker->numberBetween(0, 9);
            $scenarioIndex = $faker->numberBetween(0, $scenarioCount - 1);
            $key = $userIndex . '_' . $scenarioIndex;
            
            $attempts++;
            
            if (in_array($key, $existingRatings)) { 
                continue; 
            }
            
            $existingRatings[] = $key;

            $rating = new Rating();
            $rating->setScore($faker->numberBetween(1, 5)); 
            
            $rating->setUser($this->getReference('user_' . $userIndex, User::class));
            $rating->setScenario($this->getReference('scenario_' . $scenarioIndex, Scenario::class));

            $rating->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));
            $rating->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));

            $manager->persist($rating);
            $this->addReference('rating_' . $created, $rating); 
            
            $created++;
        }

        $manager->flush();
    }
}
