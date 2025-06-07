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
use App\Repository\CampaignRepository;
use App\State\Processor\CampaignProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['campaign:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['campaign:read']],
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['campaign:write']],
            security: "is_granted('ROLE_USER')",
            processor: CampaignProcessor::class,
            securityMessage: "Seuls les utilisateurs connectés peuvent partager des scénarios"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['campaign:write']],
            security: "is_granted('CAMPAIGN_EDIT', object)",
            securityMessage: "Vous ne pouvez modifier que vos propres scénarios"
        ),
        new Delete(
            security: "is_granted('CAMPAIGN_DELETE', object)",
            securityMessage: "Vous ne pouvez supprimer que vos propres scénarios"
        ),
    ]
)]
class Campaign
{
    use TimestampableTrait;
    use BlamableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['campaign:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?string $theme = null;

    /**
     * @var Collection<int, Scenario>
     */
    #[ORM\ManyToMany(targetEntity: Scenario::class, mappedBy: 'campaign')]
    #[Groups(['campaign:read'])]
    private Collection $scenarios;

    #[ORM\ManyToOne(inversedBy: 'campaigns')]
    #[Groups(['campaign:read'])]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->scenarios = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection<int, Scenario>
     */
    public function getScenarios(): Collection
    {
        return $this->scenarios;
    }

    public function addScenario(Scenario $scenario): static
    {
        if (!$this->scenarios->contains($scenario)) {
            $this->scenarios->add($scenario);
            $scenario->addCampaign($this);
        }

        return $this;
    }

    public function removeScenario(Scenario $scenario): static
    {
        if ($this->scenarios->removeElement($scenario)) {
            $scenario->removeCampaign($this);
        }

        return $this;
    }
}
