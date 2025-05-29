<?php

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\Scenario;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ScenarioFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CampaignFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $json = file_get_contents(__DIR__ . '/datas.json');
        $data = json_decode($json, true);

        foreach ($data['scenarios'] as $i => $scenarioData) {
            $scenario = new Scenario();
            $scenario->setTitle($scenarioData['title']);
            $scenario->setContent($scenarioData['content']);
            $scenario->setCreatedAt(new \DateTimeImmutable());
            $scenario->setUpdatedAt(new \DateTimeImmutable());

            $user = $this->getReference('user_' . $faker->numberBetween(0, 9), User::class);
            $scenario->setUser($user);

            if (!empty($scenarioData['campaign'])) {
                $scenario->addCampaign($this->getReference('campaign_' . $scenarioData['campaign'], Campaign::class));
            }

            $manager->persist($scenario);
            $this->addReference('scenario_' . $i, $scenario);
        }

        $manager->flush();
    }
}
