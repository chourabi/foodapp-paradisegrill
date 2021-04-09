<?php

namespace App\Entity;

use App\Repository\OptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionsRepository::class)
 */
class Options
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreMaximumOption;

    /**
     * @ORM\Column(type="float")
     */
    private $prixOptionSupp;

    /**
     * @ORM\OneToMany(targetEntity=ItemsToOptions::class, mappedBy="linkedOption")
     */
    private $linkedItem;

    public function __construct()
    {
        $this->linkedItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNombreMaximumOption(): ?int
    {
        return $this->nombreMaximumOption;
    }

    public function setNombreMaximumOption(int $nombreMaximumOption): self
    {
        $this->nombreMaximumOption = $nombreMaximumOption;

        return $this;
    }

    public function getPrixOptionSupp(): ?float
    {
        return $this->prixOptionSupp;
    }

    public function setPrixOptionSupp(float $prixOptionSupp): self
    {
        $this->prixOptionSupp = $prixOptionSupp;

        return $this;
    }

    /**
     * @return Collection|ItemsToOptions[]
     */
    public function getLinkedItem(): Collection
    {
        return $this->linkedItem;
    }

    public function addLinkedItem(ItemsToOptions $linkedItem): self
    {
        if (!$this->linkedItem->contains($linkedItem)) {
            $this->linkedItem[] = $linkedItem;
            $linkedItem->setLinkedOption($this);
        }

        return $this;
    }

    public function removeLinkedItem(ItemsToOptions $linkedItem): self
    {
        if ($this->linkedItem->removeElement($linkedItem)) {
            // set the owning side to null (unless already changed)
            if ($linkedItem->getLinkedOption() === $this) {
                $linkedItem->setLinkedOption(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nom;
    }
}
