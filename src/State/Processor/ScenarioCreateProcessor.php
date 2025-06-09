<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Scenario\ScenarioCreateInput;
use App\Dto\Scenario\ScenarioDetailOutput;
use App\Entity\Scenario;
use App\Repository\CampaignRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly CampaignRepository $campaignRepository
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof ScenarioCreateInput) {
            throw new \InvalidArgumentException('Expected ScenarioCreateInput');
        }

        $user = $this->security->getUser();
        if (!$user) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('User not authenticated');
        }

        $scenario = new Scenario();
        $scenario->setTitle($data->title);
        $scenario->setContent($data->content);
        $scenario->setUser($user);
        $scenario->setCreatedAt(new \DateTimeImmutable());
        $scenario->setUpdatedAt(new \DateTimeImmutable());

        if (!empty($data->campaignIds)) {
            foreach ($data->campaignIds as $campaignId) {
                $campaign = $this->campaignRepository->find($campaignId);
                if ($campaign) {
                    $scenario->addCampaign($campaign);
                }
            }
        }

        $this->entityManager->persist($scenario);
        $this->entityManager->flush();

        return ScenarioDetailOutput::fromEntity($scenario, $user);
    }
}