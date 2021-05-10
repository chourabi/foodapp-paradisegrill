<?php

namespace App\Entity;

use App\Repository\OptionToProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionToProductsRepository::class)
 */
class OptionToProducts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="productOption")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Options::class, inversedBy="optionToProducts")
     */
    private $productOption;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProductOption(): ?Options
    {
        return $this->productOption;
    }

    public function setProductOption(?Options $productOption): self
    {
        $this->productOption = $productOption;

        return $this;
    }
}
