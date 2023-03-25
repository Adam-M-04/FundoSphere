<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fundraising::class)]
    private Collection $fundraisings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Donation::class)]
    private Collection $donations;

    public function __construct()
    {
        $this->fundraisings = new ArrayCollection();
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Fundraising>
     */
    public function getFundraisings(): Collection
    {
        return $this->fundraisings;
    }

    public function addFundraising(Fundraising $fundraising): self
    {
        if (!$this->fundraisings->contains($fundraising)) {
            $this->fundraisings->add($fundraising);
            $fundraising->setFundraiserUser($this);
        }

        return $this;
    }

    public function removeFundraising(Fundraising $fundraising): self
    {
        if ($this->fundraisings->removeElement($fundraising)) {
            // set the owning side to null (unless already changed)
            if ($fundraising->getFundraiserUser() === $this) {
                $fundraising->setFundraiserUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonations(Donation $donations): self
    {
        if (!$this->donations->contains($donations)) {
            $this->donations->add($donations);
            $donations->setDonatingUser($this);
        }

        return $this;
    }

    public function removeDonations(Donation $donations): self
    {
        if ($this->donations->removeElement($donations)) {
            // set the owning side to null (unless already changed)
            if ($donations->getDonatingUser() === $this) {
                $donations->setDonatingUser(null);
            }
        }

        return $this;
    }
}
