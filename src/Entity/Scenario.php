<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DataPersister\ScenarioDataPersister;
use App\Repository\ScenarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ScenarioRepository::class)]
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
            processor: ScenarioDataPersister::class,
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

class Scenario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['scenario:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'scenarios')]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
