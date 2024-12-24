<?php

namespace App\Entity;

use App\Repository\FournisseurAdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurAdresseRepository::class)]
class FournisseurAdresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fournisseurAdresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'fournisseurAdresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isApproved = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;

        return $this;
    }
}
