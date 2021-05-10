<?php

namespace App\Entity;

use App\Repository\TableOrdreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableOrdreRepository::class)
 */
class TableOrdre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tables::class, inversedBy="tableOrdres")
     */
    private $tableRef;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrdre::class, mappedBy="ordre")
     */
    private $productOrdres;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addDate;

    public function __construct()
    {
        $this->productOrdres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTableRef(): ?Tables
    {
        return $this->tableRef;
    }

    public function setTableRef(?Tables $tableRef): self
    {
        $this->tableRef = $tableRef;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|ProductOrdre[]
     */
    public function getProductOrdres(): Collection
    {
        return $this->productOrdres;
    }

    public function addProductOrdres(ProductOrdre $productOrdres): self
    {
        if (!$this->productOrdres->contains($productOrdres)) {
            $this->productOrdres[] = $productOrdres;
            $productOrdres->setOrdre($this);
        }

        return $this;
    }

    public function removeProductOrdres(ProductOrdre $ProductOrdres): self
    {
        if ($this->ProductOrdres->removeElement($ProductOrdres)) {
            // set the owning side to null (unless already changed)
            if ($ProductOrdres->getOrdre() === $this) {
                $ProductOrdres->setOrdre(null);
            }
        }

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->addDate;
    }

    public function setAddDate(\DateTimeInterface $addDate): self
    {
        $this->addDate = $addDate;

        return $this;
    }
}
