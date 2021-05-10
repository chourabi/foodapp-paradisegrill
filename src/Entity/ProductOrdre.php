<?php

namespace App\Entity;

use App\Repository\ProductOrdreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductOrdreRepository::class)
 */
class ProductOrdre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="productOrdres")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=TableOrdre::class, inversedBy="quantity")
     */
    private $ordre;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $otherInfo;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrdreItems::class, mappedBy="productOrdre")
     */
    private $productOrdreItems;

    /**
     * @ORM\Column(type="float")
     */
    private $unitPrice;

    public function __construct()
    {
        $this->productOrdreItems = new ArrayCollection();
    }

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

    public function getOrdre(): ?TableOrdre
    {
        return $this->ordre;
    }

    public function setOrdre(?TableOrdre $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOtherInfo(): ?string
    {
        return $this->otherInfo;
    }

    public function setOtherInfo(?string $otherInfo): self
    {
        $this->otherInfo = $otherInfo;

        return $this;
    }

    /**
     * @return Collection|ProductOrdreItems[]
     */
    public function getProductOrdreItems(): Collection
    {
        return $this->productOrdreItems;
    }

    public function addProductOrdreItem(ProductOrdreItems $productOrdreItem): self
    {
        if (!$this->productOrdreItems->contains($productOrdreItem)) {
            $this->productOrdreItems[] = $productOrdreItem;
            $productOrdreItem->setProductOrdre($this);
        }

        return $this;
    }

    public function removeProductOrdreItem(ProductOrdreItems $productOrdreItem): self
    {
        if ($this->productOrdreItems->removeElement($productOrdreItem)) {
            // set the owning side to null (unless already changed)
            if ($productOrdreItem->getProductOrdre() === $this) {
                $productOrdreItem->setProductOrdre(null);
            }
        }

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}
