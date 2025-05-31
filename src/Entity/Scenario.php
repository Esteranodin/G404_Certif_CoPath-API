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
use App\Repository\ScenarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    use TimestampableTrait;
    use BlamableTrait;
    
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

    /**
     * @var Collection<int, Campaign>
     */
    #[ORM\ManyToMany(targetEntity: Campaign::class, inversedBy: 'scenarios')]
    #[Groups(['scenario:read', 'scenario:write'])]
    private Collection $campaign;

    /**
     * @var Collection<int, Music>
     */
    #[ORM\OneToMany(targetEntity: Music::class, mappedBy: 'scenario')]
    private Collection $music;

    /**
     * @var Collection<int, ImgScenario>
     */
    #[ORM\OneToMany(targetEntity: ImgScenario::class, mappedBy: 'scenario')]
    #[Groups(['scenario:read', 'scenario:write'])]
    private Collection $img;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'scenario')]
    private Collection $ratings;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'scenario')]
    private Collection $favorites;


    public function __construct()
    {
        $this->campaign = new ArrayCollection();
        $this->music = new ArrayCollection();
        $this->img = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

     #[Groups(['scenario:read'])]
    public function getAverageRating(): float
    {
        if ($this->ratings->isEmpty()) {
            return 0;
        }
        
        $total = 0;
        foreach ($this->ratings as $rating) {
            $total += $rating->getScore();
        }
        
        return round($total / $this->ratings->count(), 1);
    }
    
    #[Groups(['scenario:read'])]
    public function getRatingsCount(): int
    {
        return $this->ratings->count();
    }
    
    #[Groups(['scenario:read'])]
    public function getFavoritesCount(): int
    {
        return $this->favorites->count();
    }

    public function __toString(): string
    {
        return $this->title;
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Campaign>
     */
    public function getCampaign(): Collection
    {
        return $this->campaign;
    }

    public function addCampaign(Campaign $campaign): static
    {
        if (!$this->campaign->contains($campaign)) {
            $this->campaign->add($campaign);
        }

        return $this;
    }

    public function removeCampaign(Campaign $campaign): static
    {
        $this->campaign->removeElement($campaign);

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusic(): Collection
    {
        return $this->music;
    }

    public function addMusic(Music $music): static
    {
        if (!$this->music->contains($music)) {
            $this->music->add($music);
            $music->setScenario($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): static
    {
        if ($this->music->removeElement($music)) {
            // set the owning side to null (unless already changed)
            if ($music->getScenario() === $this) {
                $music->setScenario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImgScenario>
     */
    public function getImg(): Collection
    {
        return $this->img;
    }

    public function addImg(ImgScenario $img): static
    {
        if (!$this->img->contains($img)) {
            $this->img->add($img);
            $img->setScenario($this);
        }

        return $this;
    }

    public function removeImg(ImgScenario $img): static
    {
        if ($this->img->removeElement($img)) {
            // set the owning side to null (unless already changed)
            if ($img->getScenario() === $this) {
                $img->setScenario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setScenario($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getScenario() === $this) {
                $rating->setScenario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setScenario($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getScenario() === $this) {
                $favorite->setScenario(null);
            }
        }

        return $this;
    }
}
