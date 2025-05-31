<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DataPersister\ScenarioDataPersister;
use App\Entity\Traits\BlamableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ImgScenarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ImgScenarioRepository::class)]
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
class ImgScenario
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
    private ?string $imgPath = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['scenario:read', 'scenario:write'])]
    private ?string $imgAlt = null;

    #[ORM\ManyToOne(inversedBy: 'img')]
    private ?Scenario $scenario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): static
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getImgAlt(): ?string
    {
        return $this->imgAlt;
    }

    public function setImgAlt(?string $imgAlt): static
    {
        $this->imgAlt = $imgAlt;

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
