<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
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
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=SubCategories::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photoURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prepTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPromoted;

    /**
     * @ORM\OneToMany(targetEntity=OptionToProducts::class, mappedBy="product")
     */
    private $productOption;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrdre::class, mappedBy="product")
     */
    private $productOrdres;

    public function __construct()
    {
        $this->productOption = new ArrayCollection();
        $this->productOrdres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCategory(): ?SubCategories
    {
        return $this->category;
    }

    public function setCategory(?SubCategories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPhotoURL(): ?string
    {
        return $this->photoURL;
    }

    public function setPhotoURL(string $photoURL): self
    {
        $this->photoURL = $photoURL;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prepTime;
    }

    public function setPrepTime(?int $prepTime): self
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getIsPromoted(): ?bool
    {
        return $this->isPromoted;
    }

    public function setIsPromoted(bool $isPromoted): self
    {
        $this->isPromoted = $isPromoted;

        return $this;
    }

    /**
     * @return Collection|OptionToProducts[]
     */
    public function getProductOption(): Collection
    {
        return $this->productOption;
    }

    public function addProductOption(OptionToProducts $productOption): self
    {
        if (!$this->productOption->contains($productOption)) {
            $this->productOption[] = $productOption;
            $productOption->setProduct($this);
        }

        return $this;
    }

    public function removeProductOption(OptionToProducts $productOption): self
    {
        if ($this->productOption->removeElement($productOption)) {
            // set the owning side to null (unless already changed)
            if ($productOption->getProduct() === $this) {
                $productOption->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }

    /**
     * @return Collection|ProductOrdre[]
     */
    public function getProductOrdres(): Collection
    {
        return $this->productOrdres;
    }

    public function addProductOrdre(ProductOrdre $productOrdre): self
    {
        if (!$this->productOrdres->contains($productOrdre)) {
            $this->productOrdres[] = $productOrdre;
            $productOrdre->setProduct($this);
        }

        return $this;
    }

    public function removeProductOrdre(ProductOrdre $productOrdre): self
    {
        if ($this->productOrdres->removeElement($productOrdre)) {
            // set the owning side to null (unless already changed)
            if ($productOrdre->getProduct() === $this) {
                $productOrdre->setProduct(null);
            }
        }

        return $this;
    }
}
