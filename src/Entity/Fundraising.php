<?php

namespace App\Entity;

use App\Repository\FundraisingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundraisingRepository::class)]
class Fundraising
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $goal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_path = null;

    #[ORM\ManyToOne(inversedBy: 'fundraisings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $fundraiserUser = null;

    #[ORM\OneToMany(mappedBy: 'fundraiser', targetEntity: Donation::class, orphanRemoval: true)]
    private Collection $donations;

    public function __construct()
    {
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGoal(): ?int
    {
        return $this->goal;
    }

    public function setGoal(int $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): self
    {
        $this->image_path = $image_path;

        return $this;
    }

    public function getFundraiserUser(): ?User
    {
        return $this->fundraiserUser;
    }

    public function setFundraiserUser(?User $fundraiserUser): self
    {
        $this->fundraiserUser = $fundraiserUser;

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): self
    {
        if (!$this->donations->contains($donation)) {
            $this->donations->add($donation);
            $donation->setFundraiser($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): self
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getFundraiser() === $this) {
                $donation->setFundraiser(null);
            }
        }

        return $this;
    }

    public function getDonatedAmount(): Int
    {
        $to_return = 0;
        foreach ( $this->donations as $donation ){
            $to_return += $donation->getAmount();
        }
        return $to_return;
    }

    public function calculateCollectedPercent($limit = false): Int
    {
        $percent = (int)(($this->getDonatedAmount() / $this->goal) * 100);
        if ($limit) return min(100, max(10, $percent));
        return $percent;
    }

    public function calculateRemainingTime(): string
    {
        $date1 = new \DateTime('today');
        $interval = $date1->diff($this->deadline);
        return ($interval->m + $interval->y*12)." month" .($interval->m > 1 ? 's' : ''). ", "
            .$interval->d." day" .($interval->d > 1 ? 's' : ''). " left";
    }
}
