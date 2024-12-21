<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity()]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ligne1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ligne2 = null;

    #[ORM\Column(length: 5)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $cedex = null;

    #[ORM\Column(type: UlidType::NAME)]

    private Ulid $ulid;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, FournisseurAdresse>
     */
    #[ORM\OneToMany(targetEntity: FournisseurAdresse::class, mappedBy: 'adresse')]
    private Collection $fournisseurAdresses;

    /**
     * @var Collection<int, UserAdresse>
     */
    #[ORM\OneToMany(targetEntity: UserAdresse::class, mappedBy: 'adresse')]
    private Collection $userAdresses;

    public function __construct()
    {
        $this->ulid = new Ulid();
        $this->createdAt = new DateTimeImmutable();
        $this->fournisseurAdresses = new ArrayCollection();
        $this->userAdresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne1(): ?string
    {
        return $this->ligne1;
    }

    public function setLigne1(string $ligne1): static
    {
        $this->ligne1 = $ligne1;

        return $this;
    }

    public function getLigne2(): ?string
    {
        return $this->ligne2;
    }

    public function setLigne2(?string $ligne2): static
    {
        $this->ligne2 = $ligne2;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getCedex(): ?string
    {
        return $this->cedex;
    }

    public function setCedex(?string $cedex): static
    {
        $this->cedex = $cedex;

        return $this;
    }
    public function getUlid(): ?Ulid
    {
        return $this->ulid;
    }

    public function setUlid(): static
    {
        $this->ulid;

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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

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
            $fournisseurAdress->setAdresse($this);
        }

        return $this;
    }

    public function removeFournisseurAdress(FournisseurAdresse $fournisseurAdress): static
    {
        if ($this->fournisseurAdresses->removeElement($fournisseurAdress)) {
            // set the owning side to null (unless already changed)
            if ($fournisseurAdress->getAdresse() === $this) {
                $fournisseurAdress->setAdresse(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, UserAdresse>
    //  */
    public function getUserAdresses(): Collection
    {
        return $this->userAdresses;
    }

    public function addUserAdress(UserAdresse $userAdress): static
    {
        if (!$this->userAdresses->contains($userAdress)) {
            $this->userAdresses->add($userAdress);
            $userAdress->setAdresse($this);
        }

        return $this;
    }

    public function removeUserAdress(UserAdresse $userAdress): static
    {
        if ($this->userAdresses->removeElement($userAdress)) {
            // set the owning side to null (unless already changed)
            if ($userAdress->getAdresse() === $this) {
                $userAdress->setAdresse(null);
            }
        }

        return $this;
    }
}
