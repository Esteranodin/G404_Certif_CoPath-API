<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\BlamableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\MusicRepository;
use App\State\Processor\ScenarioProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['scenario:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['scenario:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['scenario:write']],
            security: "is_granted('ROLE_USER')",
            processor: ScenarioProcessor::class,
            securityMessage: "Seuls les utilisateurs connectés peuvent partager des scénarios"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['scenario:write']],
            security: "is_granted('SCENARIO_EDIT', object)",
            securityMessage: "Vous ne pouvez modifier que vos propres scénarios"
        ),
        new Delete(
            security: "is_granted('SCENARIO_DELETE', object)",
            securityMessage: "Vous ne pouvez supprimer que vos propres scénarios"
        ),
    ]
)]
class Music
{
    use TimestampableTrait;
    use BlamableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['scenario:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?string $musicPath = null;

    #[ORM\ManyToOne(inversedBy: 'music')]
    #[Groups(['scenario:read'])]
    private ?Scenario $scenario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMusicPath(): ?string
    {
        return $this->musicPath;
    }

    public function setMusicPath(?string $musicPath): static
    {
        $this->musicPath = $musicPath;

        return $this;
    }

    public function getScenario(): ?Scenario
    {
        return $this->scenario;
    }

    public function setScenario(?Scenario $scenario): static
    {
        $this->scenario = $scenario;

        return $this;
    }
}
