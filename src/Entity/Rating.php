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
use App\State\Provider\UserRatingCollectionProvider;
use App\Repository\RatingRepository;
use App\State\Processor\RatingProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_RATING_USER_SCENARIO', columns: ['user_id', 'scenario_id'])]
#[UniqueEntity(
    fields: ['user', 'scenario'],
    message: 'Vous avez déjà noté ce scénario.'
)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['rating:read']],
            security: "is_granted('ROLE_USER') and object.getUser() == user"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['rating:read']],
            security: "is_granted('ROLE_USER')",
            provider: UserRatingCollectionProvider::class
        ),
        new Post(
            denormalizationContext: ['groups' => ['rating:write']],
            processor: RatingProcessor::class, 
            security: "is_granted('ROLE_USER')",
            securityMessage: "Seuls les utilisateurs connectés peuvent noter"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['rating:write']],
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            securityMessage: "Vous ne pouvez modifier que vos propres notes"
        ),
        new Delete(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            securityMessage: "Vous ne pouvez supprimer que vos propres notes"
        ),
    ]
)]
class Rating
{
    use TimestampableTrait;
    use BlamableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rating:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rating:read', 'rating:write'])]
    private ?Scenario $scenario = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rating:read'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotNull(message: 'La note est obligatoire')]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }} étoiles'
    )]
    #[Groups(['rating:read', 'rating:write'])]
    private ?int $score = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;
        return $this;
    }
}