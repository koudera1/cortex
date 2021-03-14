<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 * @ORM\Table(name="purchase")
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $purchaseDate;

    /**
     * @var LoyaltyCard
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\LoyaltyCard")
     * @ORM\JoinColumn(nullable=false)
     */
    private $loyaltyCard;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Purchase
     */
    public function setCustomer(Customer $customer): Purchase
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return LoyaltyCard
     */
    public function getLoyaltyCard(): LoyaltyCard
    {
        return $this->loyaltyCard;
    }

    /**
     * @param LoyaltyCard $loyaltyCard
     * @return Purchase
     */
    public function setLoyaltyCard(LoyaltyCard $loyaltyCard): Purchase
    {
        $this->loyaltyCard = $loyaltyCard;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPurchaseDate(): \DateTime
    {
        return $this->purchaseDate;
    }

    /**
     * @param \DateTime $purchaseDate
     * @return Purchase
     */
    public function setPurchaseDate(\DateTime $purchaseDate): Purchase
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }



}
