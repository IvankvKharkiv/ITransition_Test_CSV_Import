<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductData.
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 */
class ProductData
{
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    private string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private string $description;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $productCode;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private ?\DateTime $dateAdded;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private ?\DateTime $discontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private \DateTime $timestamp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $stock;

    /**
     * @ORM\OneToMany(targetEntity=ProductPrice::class, mappedBy="product", orphanRemoval=true, cascade={"all"})
     */
    private $productPrices;

    public function __construct(\DateTime $dateAdded = null)
    {
        if (!is_null($dateAdded)) {
            var_dump($dateAdded);

            exit();
        }

        $this->productPrices = new ArrayCollection();
        $this->timestamp = new \DateTime('now', new \DateTimeZone('GMT'));
        $this->dateAdded = $dateAdded ?: $this->timestamp;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): self
    {
        $this->productCode = $productCode;
        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(?\DateTimeInterface $dateAdded): self
    {
        $this->dateAdded = $dateAdded;
        return $this;
    }

    public function getDiscontinued(): ?\DateTimeInterface
    {
        return $this->discontinued;
    }

    public function setDiscontinued(?\DateTimeInterface $discontinued): self
    {
        $this->discontinued = $discontinued;
        return $this;
    }

    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return Collection|ProductPrice[]
     */
    public function getProductPrices(): Collection
    {
        return $this->productPrices;
    }

    public function addProductPrice(ProductPrice $productPrice): self
    {
        if (!$this->productPrices->contains($productPrice)) {
            $this->productPrices[] = $productPrice;
            $productPrice->setProduct($this);
        }

        return $this;
    }

    public function removeProductPrice(ProductPrice $productPrice): self
    {
        if ($this->productPrices->removeElement($productPrice)) {
            if ($productPrice->getProduct() === $this) {
                $productPrice->setProduct(null);
            }
        }

        return $this;
    }
}
