<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cpVille = null;

    #[ORM\Column(length: 255)]
    private ?string $nomVille = null;

    #[ORM\Column(length: 255)]
    private ?string $paysVille = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCpVille(): ?string
    {
        return $this->cpVille;
    }

    public function setCpVille(string $cpVille): self
    {
        $this->cpVille = $cpVille;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(string $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getPaysVille(): ?string
    {
        return $this->paysVille;
    }

    public function setPaysVille(string $paysVille): self
    {
        $this->paysVille = $paysVille;

        return $this;
    }
}
