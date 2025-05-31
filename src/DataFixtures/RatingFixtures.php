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
        
        for ($i = 0; $i < 50; $i++) {
            $rating = new Rating();
            
            $rating->setScore($faker->numberBetween(1, 5));
            
            $userIndex = $faker->numberBetween(0, 9);
            $user = $this->getReference('user_' . $userIndex, User::class);
            $rating->setUser($user);
            
            $scenarioIndex = $faker->numberBetween(0, $scenarioCount - 1);
            $scenario = $this->getReference('scenario_' . $scenarioIndex, Scenario::class);
            $rating->setScenario($scenario);
            
            $rating->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')));
            $rating->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($rating);
            $this->addReference('rating_' . $i, $rating);
        }

        $manager->flush();
    }
}