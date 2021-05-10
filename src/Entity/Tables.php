<?php

namespace App\Entity;

use App\Repository\TablesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TablesRepository::class)
 * @UniqueEntity("number")
 */
class Tables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $uniqueID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity=TableOrdre::class, mappedBy="tableRef")
     */
    private $tableOrdres;

    public function __construct()
    {
        $this->tableOrdres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUniqueID(): ?string
    {
        return $this->uniqueID;
    }

    public function setUniqueID(string $uniqueID): self
    {
        $this->uniqueID = $uniqueID;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|TableOrdre[]
     */
    public function getTableOrdres(): Collection
    {
        return $this->tableOrdres;
    }

    public function addTableOrdre(TableOrdre $tableOrdre): self
    {
        if (!$this->tableOrdres->contains($tableOrdre)) {
            $this->tableOrdres[] = $tableOrdre;
            $tableOrdre->setTableRef($this);
        }

        return $this;
    }

    public function removeTableOrdre(TableOrdre $tableOrdre): self
    {
        if ($this->tableOrdres->removeElement($tableOrdre)) {
            // set the owning side to null (unless already changed)
            if ($tableOrdre->getTableRef() === $this) {
                $tableOrdre->setTableRef(null);
            }
        }

        return $this;
    }
}
