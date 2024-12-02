<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;


    #[ORM\Column(length: 100, nullable: true)]
    private ?string $description = null;


    #[ORM\OneToMany(targetEntity: UserAbonnement::class, mappedBy: 'abonnement', cascade: ['persist', 'remove'])]
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
            $userAbonnement->setAbonnement($this);
        }

        return $this;
    }

    public function removeUserAbonnement(UserAbonnement $userAbonnement): self
    {
        if ($this->userAbonnements->removeElement($userAbonnement)) {
            if ($userAbonnement->getAbonnement() === $this) {
                $userAbonnement->setAbonnement(null);
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}