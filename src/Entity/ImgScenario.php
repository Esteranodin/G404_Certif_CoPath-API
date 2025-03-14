<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ImgScenarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ImgScenarioRepository::class)]
#[ApiResource]
class ImgScenario
{
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
    #[Groups(['scenario:read'])]
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
