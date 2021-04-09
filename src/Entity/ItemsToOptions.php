<?php

namespace App\Entity;

use App\Repository\ItemsToOptionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemsToOptionsRepository::class)
 */
class ItemsToOptions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Options::class, inversedBy="linkedItem")
     */
    private $linkedOption;

    /**
     * @ORM\ManyToOne(targetEntity=OptionItems::class, inversedBy="itemsToOptions")
     */
    private $linkedItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkedOption(): ?Options
    {
        return $this->linkedOption;
    }

    public function setLinkedOption(?Options $linkedOption): self
    {
        $this->linkedOption = $linkedOption;

        return $this;
    }

    public function getLinkedItem(): ?OptionItems
    {
        return $this->linkedItem;
    }

    public function setLinkedItem(?OptionItems $linkedItem): self
    {
        $this->linkedItem = $linkedItem;

        return $this;
    }
}
