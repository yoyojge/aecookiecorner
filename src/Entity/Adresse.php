<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $rueAdresse = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn]
    private ?Ville $ville = null;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Commande::class)]
    private Collection $commande;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Facture::class)]
    private Collection $facture;

    #[ORM\ManyToOne(inversedBy: 'adresse')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $villeAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $cpAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $nomAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomAdresse = null;

    #[ORM\OneToMany(mappedBy: 'adresseLivraison', targetEntity: Commande::class)]
    private Collection $commandes;

    

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->facture = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->villeAdresse." ".$this->rueAdresse;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAdresse(): ?string
    {
        return $this->typeAdresse;
    }

    public function setTypeAdresse(string $typeAdresse): self
    {
        $this->typeAdresse = $typeAdresse;

        return $this;
    }

    public function getRueAdresse(): ?string
    {
        return $this->rueAdresse;
    }

    public function setRueAdresse(string $rueAdresse): self
    {
        $this->rueAdresse = $rueAdresse;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
            $commande->setAdresse($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAdresse() === $this) {
                $commande->setAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFacture(): Collection
    {
        return $this->facture;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->facture->contains($facture)) {
            $this->facture->add($facture);
            $facture->setAdresse($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getAdresse() === $this) {
                $facture->setAdresse(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVilleAdresse(): ?string
    {
        return $this->villeAdresse;
    }

    public function setVilleAdresse(string $villeAdresse): self
    {
        $this->villeAdresse = $villeAdresse;

        return $this;
    }

    public function getCpAdresse(): ?string
    {
        return $this->cpAdresse;
    }

    public function setCpAdresse(string $cpAdresse): self
    {
        $this->cpAdresse = $cpAdresse;

        return $this;
    }

    public function getNomAdresse(): ?string
    {
        return $this->nomAdresse;
    }

    public function setNomAdresse(string $nomAdresse): self
    {
        $this->nomAdresse = $nomAdresse;

        return $this;
    }

    public function getPrenomAdresse(): ?string
    {
        return $this->prenomAdresse;
    }

    public function setPrenomAdresse(string $prenomAdresse): self
    {
        $this->prenomAdresse = $prenomAdresse;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    
}
