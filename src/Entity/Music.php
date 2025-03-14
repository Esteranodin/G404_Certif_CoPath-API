<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MusicRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
#[ApiResource]
class Music
{
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
