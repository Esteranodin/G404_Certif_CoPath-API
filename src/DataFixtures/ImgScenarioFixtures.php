<?php

namespace App\DataFixtures;

use App\Entity\ImgScenario;
use App\Entity\Scenario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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
        $json = file_get_contents(__DIR__ . '/datas.json');
        $data = json_decode($json, true);

        foreach ($data['scenarios'] as $i => $scenarioData) {
            $img = new ImgScenario();
            $img->setImgPath($scenarioData['image_path']);
            $img->setImgAlt($scenarioData['image_alt']);
            $img->setScenario($this->getReference('scenario_' . $i, Scenario::class));
            $manager->persist($img);
        }

        $manager->flush();
    }
}