<?php

namespace App\Entity;

use App\Repository\ProductOrdreItemsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductOrdreItemsRepository::class)
 */
class ProductOrdreItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductOrdre::class, inversedBy="productOrdreItems")
     */
    private $productOrdre;

    /**
     * @ORM\ManyToOne(targetEntity=OptionItems::class, inversedBy="productOrdreItems")
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity=Options::class, inversedBy="productOrdreItems")
     */
    private $optionRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductOrdre(): ?ProductOrdre
    {
        return $this->productOrdre;
    }

    public function setProductOrdre(?ProductOrdre $productOrdre): self
    {
        $this->productOrdre = $productOrdre;

        return $this;
    }

    public function getItem(): ?OptionItems
    {
        return $this->item;
    }

    public function setItem(?OptionItems $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getOptionRef(): ?Options
    {
        return $this->optionRef;
    }

    public function setOptionRef(?Options $optionRef): self
    {
        $this->optionRef = $optionRef;

        return $this;
    }
}
