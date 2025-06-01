<?php

namespace App\DataFixtures;

use App\Entity\ImgScenario;
use App\Entity\Scenario;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ImgScenarioFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ScenarioFixtures::class,
        ];
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $json = file_get_contents(__DIR__ . '/datas.json');
        $data = json_decode($json, true);

        foreach ($data['scenarios'] as $i => $scenarioData) {
            $img = new ImgScenario();
            $img->setImgPath($scenarioData['image_path']);
            $img->setImgAlt($scenarioData['image_alt']);
            $scenario = $this->getReference('scenario_' . $i, Scenario::class);
            $img->setScenario($scenario);
            $img->setUser($this->getReference('user_' . $faker->numberBetween(0, 9), User::class));
            
            $img->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween($scenario->getCreatedAt()->format('Y-m-d'), 'now')->format('Y-m-d H:i:s')));
            $img->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween($scenario->getCreatedAt()->format('Y-m-d'), 'now')->format('Y-m-d H:i:s'))); 

            $manager->persist($img);
        }

        $manager->flush();
    }
}