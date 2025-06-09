<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Scenario\ScenarioUpdateInput;
use App\Dto\Scenario\ScenarioDetailOutput;
use App\Repository\CampaignRepository;
use App\Repository\ScenarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ScenarioUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly CampaignRepository $campaignRepository,
        private readonly ScenarioRepository $scenarioRepository
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof ScenarioUpdateInput) {
            throw new \InvalidArgumentException('Expected ScenarioUpdateInput');
        }

        $scenarioId = $uriVariables['id'] ?? null;
        if (!$scenarioId) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Scenario ID missing');
        }

        $scenario = $this->scenarioRepository->find($scenarioId);
        if (!$scenario) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Scenario not found');
        }

        $user = $this->security->getUser();

        if ($data->title !== null) {
            $scenario->setTitle($data->title);
        }
        
        if ($data->content !== null) {
            $scenario->setContent($data->content);
        }

        if ($data->campaignIds !== null) {
            foreach ($scenario->getCampaign() as $campaign) {
                $scenario->removeCampaign($campaign);
            }
            
            foreach ($data->campaignIds as $campaignId) {
                $campaign = $this->campaignRepository->find($campaignId);
                if ($campaign) {
                    $scenario->addCampaign($campaign);
                }
            }
        }

        $scenario->setUpdatedAt(new \DateTimeImmutable());
        
        $this->entityManager->persist($scenario);
        $this->entityManager->flush();

        return ScenarioDetailOutput::fromEntity($scenario, $user);
    }
}