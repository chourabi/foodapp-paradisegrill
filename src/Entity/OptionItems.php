<?php

namespace App\Entity;

use App\Repository\OptionItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionItemsRepository::class)
 */
class OptionItems
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
    private $Nom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=ItemsToOptions::class, mappedBy="linkedItem")
     */
    private $itemsToOptions;

    public function __construct()
    {
        $this->itemsToOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|ItemsToOptions[]
     */
    public function getItemsToOptions(): Collection
    {
        return $this->itemsToOptions;
    }

    public function addItemsToOption(ItemsToOptions $itemsToOption): self
    {
        if (!$this->itemsToOptions->contains($itemsToOption)) {
            $this->itemsToOptions[] = $itemsToOption;
            $itemsToOption->setLinkedItem($this);
        }

        return $this;
    }

    public function removeItemsToOption(ItemsToOptions $itemsToOption): self
    {
        if ($this->itemsToOptions->removeElement($itemsToOption)) {
            // set the owning side to null (unless already changed)
            if ($itemsToOption->getLinkedItem() === $this) {
                $itemsToOption->setLinkedItem(null);
            }
        }

        return $this;
    }


    public function __toString(){
        return $this->Nom;
    }
}
