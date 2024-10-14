<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $service = null;

    /**
     * @var Collection<int, FournisseurAdresse>
     */
    #[ORM\OneToMany(targetEntity: FournisseurAdresse::class, mappedBy: 'idFournisseur')]
    private Collection $fournisseurAdresses;

    public function __construct()
    {
        $this->fournisseurAdresses = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, FournisseurAdresse>
     */
    public function getFournisseurAdresses(): Collection
    {
        return $this->fournisseurAdresses;
    }

    public function addFournisseurAdress(FournisseurAdresse $fournisseurAdress): static
    {
        if (!$this->fournisseurAdresses->contains($fournisseurAdress)) {
            $this->fournisseurAdresses->add($fournisseurAdress);
            $fournisseurAdress->setIdFournisseur($this);
        }

        return $this;
    }

    public function removeFournisseurAdress(FournisseurAdresse $fournisseurAdress): static
    {
        if ($this->fournisseurAdresses->removeElement($fournisseurAdress)) {
            // set the owning side to null (unless already changed)
            if ($fournisseurAdress->getIdFournisseur() === $this) {
                $fournisseurAdress->setIdFournisseur(null);
            }
        }

        return $this;
    }
}
