<?php

namespace App\Entity;

use App\Repository\LigneFraisForfaitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisForfaitRepository::class)]
class LigneFraisForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfait')]
    private ?FicheFrais $fichesfrais = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfaits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FraisForfait $fraisForfait = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFichesFrais(): ?FicheFrais
    {
        return $this->fichesfrais;
    }

    public function setFichesFrais(?FicheFrais $fichesfrais): static
    {
        $this->fichesfrais = $fichesfrais;

        return $this;
    }

    public function getFraisForfait(): ?FraisForfait
    {
        return $this->fraisForfait;
    }

    public function setFraisForfait(?FraisForfait $fraisForfait): static
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }

}
