<?php

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $json = file_get_contents(__DIR__ . '/datas.json');
        $data = json_decode($json, true);

        foreach ($data['campaigns'] as $campaignData) {
            $campaign = new Campaign();
            $campaign->setName($campaignData['name']);
            $campaign->setTheme($campaignData['theme']);
            $user = $this->getReference('user_' . $faker->numberBetween(0, 9), User::class);
            $campaign->setUser($user);
            $manager->persist($campaign);
            $this->addReference('campaign_' . $campaignData['theme'], $campaign);
        }

        $manager->flush();
    }
}