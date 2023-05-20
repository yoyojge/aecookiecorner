<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomArticle = null;

    #[ORM\Column]
    private ?float $prixArticle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionArticle = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Visuel::class)]
    private Collection $visuels;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: CommandeArticle::class)]
    private Collection $commandeArticles;

    public function __construct()
    {
        $this->visuels = new ArrayCollection();
        $this->commandeArticles = new ArrayCollection();
    }

    public function __toString(): string
      {
            return $this->nomArticle;
        }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomArticle(): ?string
    {
        return $this->nomArticle;
    }

    public function setNomArticle(string $nomArticle): self
    {
        $this->nomArticle = $nomArticle;

        return $this;
    }

    public function getPrixArticle(): ?float
    {
        return $this->prixArticle;
    }

    public function setPrixArticle(float $prixArticle): self
    {
        $this->prixArticle = $prixArticle;

        return $this;
    }

    public function getDescriptionArticle(): ?string
    {
        return $this->descriptionArticle;
    }

    public function setDescriptionArticle(?string $descriptionArticle): self
    {
        $this->descriptionArticle = $descriptionArticle;

        return $this;
    }

    /**
     * @return Collection<int, Visuel>
     */
    public function getVisuels(): Collection
    {
        return $this->visuels;
    }

    public function addVisuel(Visuel $visuel): self
    {
        if (!$this->visuels->contains($visuel)) {
            $this->visuels->add($visuel);
            $visuel->setArticle($this);
        }

        return $this;
    }

    public function removeVisuel(Visuel $visuel): self
    {
        if ($this->visuels->removeElement($visuel)) {
            // set the owning side to null (unless already changed)
            if ($visuel->getArticle() === $this) {
                $visuel->setArticle(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, CommandeArticle>
     */
    public function getCommandeArticles(): Collection
    {
        return $this->commandeArticles;
    }

    public function addCommandeArticle(CommandeArticle $commandeArticle): self
    {
        if (!$this->commandeArticles->contains($commandeArticle)) {
            $this->commandeArticles->add($commandeArticle);
            $commandeArticle->setArticle($this);
        }

        return $this;
    }

    public function removeCommandeArticle(CommandeArticle $commandeArticle): self
    {
        if ($this->commandeArticles->removeElement($commandeArticle)) {
            // set the owning side to null (unless already changed)
            if ($commandeArticle->getArticle() === $this) {
                $commandeArticle->setArticle(null);
            }
        }

        return $this;
    }
}
