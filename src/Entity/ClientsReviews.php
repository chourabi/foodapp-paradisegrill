<?php

namespace App\Entity;

use App\Repository\ClientsReviewsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientsReviewsRepository::class)
 */
class ClientsReviews
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
    private $clientEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientReview;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSeen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    public function getClientReview(): ?string
    {
        return $this->clientReview;
    }

    public function setClientReview(string $clientReview): self
    {
        $this->clientReview = $clientReview;

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

    public function getIsSeen(): ?bool
    {
        return $this->isSeen;
    }

    public function setIsSeen(bool $isSeen): self
    {
        $this->isSeen = $isSeen;

        return $this;
    }
}
