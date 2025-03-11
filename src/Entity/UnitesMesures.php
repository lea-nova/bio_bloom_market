<?php

namespace App\Entity;

use App\Enums\CategorieUnitesMesure;
use App\Repository\UnitesMesuresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitesMesuresRepository::class)]
class UnitesMesures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $symbole = null;

    #[ORM\Column]
    private ?float $facteurConversion = null;

    #[ORM\Column(enumType: CategorieUnitesMesure::class)]
    private ?CategorieUnitesMesure $categorieMesure = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'uniteMesure')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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

    public function getSymbole(): ?string
    {
        return $this->symbole;
    }

    public function setSymbole(string $symbole): static
    {
        $this->symbole = $symbole;

        return $this;
    }

    public function getFacteurConversion(): ?float
    {
        return $this->facteurConversion;
    }

    public function setFacteurConversion(float $facteurConversion): static
    {
        $this->facteurConversion = $facteurConversion;

        return $this;
    }

    public function getCategorieMesure(): ?CategorieUnitesMesure
    {
        return $this->categorieMesure;
    }

    public function setCategorieMesure(CategorieUnitesMesure $categorieMesure): static
    {
        $this->categorieMesure = $categorieMesure;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setUniteMesure($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUniteMesure() === $this) {
                $produit->setUniteMesure(null);
            }
        }

        return $this;
    }
}
