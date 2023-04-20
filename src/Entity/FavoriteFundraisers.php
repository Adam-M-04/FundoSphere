<?php

namespace App\Entity;

use App\Repository\FavoriteFundraisersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteFundraisersRepository::class)]
class FavoriteFundraisers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoriteFundraiserId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fundraising $favoriteFundraiser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFavoriteFundraiser(): ?Fundraising
    {
        return $this->favoriteFundraiser;
    }

    public function setFavoriteFundraiser(?Fundraising $favoriteFundraiser): self
    {
        $this->favoriteFundraiser = $favoriteFundraiser;

        return $this;
    }
}
