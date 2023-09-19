<?php

namespace App\Entity;

use App\Repository\FicheFraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheFraisRepository::class)]
class FicheFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mois = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbJustificatifs = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModif = null;

    #[ORM\ManyToOne(inversedBy: 'fichesFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(inversedBy: 'fichesFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'fichesFrais', targetEntity: LigneFraisHorsForfait::class)]
    private Collection $LignefraisHorsForfait;

    #[ORM\OneToMany(mappedBy: 'fichesfrais', targetEntity: LigneFraisForfait::class)]
    private Collection $ligneFraisForfait;

    public function __construct()
    {
        $this->LignefraisHorsForfait = new ArrayCollection();
        $this->ligneFraisForfait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(?string $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getNbJustificatifs(): ?int
    {
        return $this->nbJustificatifs;
    }

    public function setNbJustificatifs(?int $nbJustificatifs): static
    {
        $this->nbJustificatifs = $nbJustificatifs;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, LigneFraisHorsForfait>
     */
    public function getLignefraisHorsForfait(): Collection
    {
        return $this->LignefraisHorsForfait;
    }

    public function addLignefraisHorsForfait(LigneFraisHorsForfait $lignefraisHorsForfait): static
    {
        if (!$this->LignefraisHorsForfait->contains($lignefraisHorsForfait)) {
            $this->LignefraisHorsForfait->add($lignefraisHorsForfait);
            $lignefraisHorsForfait->setFichesFrais($this);
        }

        return $this;
    }

    public function removeLignefraisHorsForfait(LigneFraisHorsForfait $lignefraisHorsForfait): static
    {
        if ($this->LignefraisHorsForfait->removeElement($lignefraisHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($lignefraisHorsForfait->getFichesFrais() === $this) {
                $lignefraisHorsForfait->setFichesFrais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneFraisForfait>
     */
    public function getLigneFraisForfait(): Collection
    {
        return $this->ligneFraisForfait;
    }

    public function addLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): static
    {
        if (!$this->ligneFraisForfait->contains($ligneFraisForfait)) {
            $this->ligneFraisForfait->add($ligneFraisForfait);
            $ligneFraisForfait->setFichesfrais($this);
        }

        return $this;
    }

    public function removeLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): static
    {
        if ($this->ligneFraisForfait->removeElement($ligneFraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisForfait->getFichesfrais() === $this) {
                $ligneFraisForfait->setFichesfrais(null);
            }
        }

        return $this;
    }
}
