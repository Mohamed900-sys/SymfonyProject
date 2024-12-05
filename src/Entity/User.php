<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User  implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 250)]
    private ?string $password = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;


    #[ORM\OneToMany(targetEntity: Salle::class, mappedBy: 'owner')]
    private Collection $gymsOwned;

    // #[ORM\ManyToOne(targetEntity: Salle::class, inversedBy: 'members')]
    // #[ORM\JoinColumn(nullable: true, name: 'training_area_id', referencedColumnName: 'id')]
    // private ?Salle $trainingArea = null;


    #[ORM\OneToMany(targetEntity: UserAbonnement::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private $userAbonnements;

    public function __construct()
    {
        $this->userAbonnements = new ArrayCollection();
    }

    public function getUserAbonnements(): Collection
    {
        return $this->userAbonnements;
    }

    public function addUserAbonnement(UserAbonnement $userAbonnement): self
    {
        if (!$this->userAbonnements->contains($userAbonnement)) {
            $this->userAbonnements[] = $userAbonnement;
            $userAbonnement->setUser($this);
        }

        return $this;
    }

    public function removeUserAbonnement(UserAbonnement $userAbonnement): self
    {
        if ($this->userAbonnements->removeElement($userAbonnement)) {
            if ($userAbonnement->getUser() === $this) {
                $userAbonnement->setUser(null);
            }
        }

        return $this;
    }





    // Getters and Setters


    public function getGymsOwned(): Collection
    {
        return $this->gymsOwned;
    }

    public function addGymOwned(Salle $gym): self
    {
        if (!$this->gymsOwned->contains($gym)) {
            $this->gymsOwned->add($gym);
            $gym->setOwner($this);
        }
        return $this;
    }

    public function removeGymOwned(Salle $gym): self
    {
        if ($this->gymsOwned->removeElement($gym)) {
            if ($gym->getOwner() === $this) {
                $gym->setOwner(null);
            }
        }
        return $this;
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
