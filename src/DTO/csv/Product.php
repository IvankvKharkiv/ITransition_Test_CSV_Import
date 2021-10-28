<?php

declare(strict_types=1);

namespace App\DTO\csv;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Product
{
    /**
     * @var string
     * @SerializedName("Product Code")
     * @Assert\Length(
     *      min = 1,
     *      max = 10,
     *      minMessage = "Product code must be at least {{ limit }} symbols",
     *      maxMessage = "Product code must not be more than {{ limit }} symbols"
     * )
     */
    public $productCode;
    /**
     * @var string
     * @SerializedName("Product Name")
     * @Assert\NotBlank
     */
    public $name;
    /**
     * @var string
     * @SerializedName("Product Description")
     */
    public $description;
    /**
     * @var mixed
     * @SerializedName("Stock")
     * @Assert\Type("integer")
     */
    public $stock;
    /**
     * @var mixed
     * @SerializedName("Cost in GBP")
     * @Assert\Type("float")
     * @Assert\NotEqualTo(0)
     */
    public $costInGBP;
    /**
     * @var mixed
     * @SerializedName("Discontinued")
     * @Assert\Regex("/(?i)\byes\b/")
     */
    public $discontinued;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addGetterConstraint('BothPriceStockInValid', new Assert\IsFalse(['message' => 'Both price must be more that $5 and quantity must be more than 10pcs in stock']));
    }

    /**
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @param string $productCode
     */
    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock): void
    {
        if (is_numeric($stock)) {
            $this->stock = intval($stock);
        } else {
            $this->stock = $stock;
        }
    }

    /**
     * @return mixed
     */
    public function getCostInGBP()
    {
        return $this->costInGBP;
    }

    /**
     * @param mixed $costInGBP
     */
    public function setCostInGBP($costInGBP): void
    {
        if (is_numeric($costInGBP)) {
            $this->costInGBP = floatval($costInGBP);
        } else {
            $this->costInGBP = $costInGBP;
        }
    }

    /**
     * @return mixed
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * @param mixed $discontinued
     */
    public function setDiscontinued($discontinued): void
    {
        $this->discontinued = $discontinued;
    }

    public function isBothPriceStockInValid()
    {
        return ($this->costInGBP < 5) && ($this->stock < 10);
    }
}
