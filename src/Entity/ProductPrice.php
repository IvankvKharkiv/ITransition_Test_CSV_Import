<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductPriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductPriceRepository::class)
 */
class ProductPrice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductData::class, inversedBy="productPrices")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="intProductDataId")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class, inversedBy="productPrices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?ProductData
    {
        return $this->product;
    }

    public function setProduct(?ProductData $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
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
}
