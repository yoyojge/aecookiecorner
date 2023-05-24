<?php

namespace App\Entity;

use App\Repository\VisuelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisuelRepository::class)]
class Visuel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cheminVisuel = null;

    #[ORM\ManyToOne(inversedBy: 'visuels')]
    private ?Article $article = null;

    public function __toString(): string
    {
        return $this->cheminVisuel;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheminVisuel(): ?string
    {
        return $this->cheminVisuel;
    }

    public function setCheminVisuel(string $cheminVisuel): self
    {
        $this->cheminVisuel = $cheminVisuel;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
