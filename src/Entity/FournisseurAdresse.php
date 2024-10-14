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
    private ?Fournisseur $idFournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'fournisseurAdresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $idAdresse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFournisseur(): ?Fournisseur
    {
        return $this->idFournisseur;
    }

    public function setIdFournisseur(?Fournisseur $idFournisseur): static
    {
        $this->idFournisseur = $idFournisseur;

        return $this;
    }

    public function getIdAdresse(): ?Adresse
    {
        return $this->idAdresse;
    }

    public function setIdAdresse(?Adresse $idAdresse): static
    {
        $this->idAdresse = $idAdresse;

        return $this;
    }
}
