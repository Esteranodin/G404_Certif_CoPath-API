<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Traits\BlamableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\RatingRepository;
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
            security: "is_granted('ROLE_USER')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['rating:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Post(
            denormalizationContext: ['groups' => ['rating:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Seuls les utilisateurs connectés peuvent noter"
        ),
        new Put(
            denormalizationContext: ['groups' => ['rating:write']],
            security: "is_granted('RATING_EDIT', object)",
            securityMessage: "Vous ne pouvez modifier que vos propres notes"
        ),
        new Delete(
            security: "is_granted('RATING_DELETE', object)",
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

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }} étoiles'
    )]
    #[Groups(['rating:read', 'rating:write'])]
    private ?int $score = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['rating:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['rating:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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