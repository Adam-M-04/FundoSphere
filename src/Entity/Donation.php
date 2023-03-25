<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $donationDate = null;

    #[ORM\ManyToOne(inversedBy: 'fundraiser')]
    private ?User $donatingUser = null;

    #[ORM\ManyToOne(inversedBy: 'donations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fundraising $fundraiser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDonationDate(): ?\DateTimeInterface
    {
        return $this->donationDate;
    }

    public function setDonationDate(\DateTimeInterface $donationDate): self
    {
        $this->donationDate = $donationDate;

        return $this;
    }

    public function getDonatingUser(): ?User
    {
        return $this->donatingUser;
    }

    public function setDonatingUser(?User $donatingUser): self
    {
        $this->donatingUser = $donatingUser;

        return $this;
    }

    public function getFundraiser(): ?Fundraising
    {
        return $this->fundraiser;
    }

    public function setFundraiser(?Fundraising $fundraiser): self
    {
        $this->fundraiser = $fundraiser;

        return $this;
    }
}
