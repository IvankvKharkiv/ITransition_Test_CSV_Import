<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tblproductdata
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 */
class Tblproductdata
{
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $intproductdataid;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    private $strproductname;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $strproductdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $strproductcode;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmadded;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmdiscontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $stmtimestamp = 'CURRENT_TIMESTAMP';

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price_gbp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    public function getIntproductdataid(): ?int
    {
        return $this->intproductdataid;
    }

    public function getStrproductname(): ?string
    {
        return $this->strproductname;
    }

    public function setStrproductname(string $strproductname): self
    {
        $this->strproductname = $strproductname;

        return $this;
    }

    public function getStrproductdesc(): ?string
    {
        return $this->strproductdesc;
    }

    public function setStrproductdesc(string $strproductdesc): self
    {
        $this->strproductdesc = $strproductdesc;

        return $this;
    }

    public function getStrproductcode(): ?string
    {
        return $this->strproductcode;
    }

    public function setStrproductcode(string $strproductcode): self
    {
        $this->strproductcode = $strproductcode;

        return $this;
    }

    public function getDtmadded(): ?\DateTimeInterface
    {
        return $this->dtmadded;
    }

    public function setDtmadded(?\DateTimeInterface $dtmadded): self
    {
        $this->dtmadded = $dtmadded;

        return $this;
    }

    public function getDtmdiscontinued(): ?\DateTimeInterface
    {
        return $this->dtmdiscontinued;
    }

    public function setDtmdiscontinued(?\DateTimeInterface $dtmdiscontinued): self
    {
        $this->dtmdiscontinued = $dtmdiscontinued;

        return $this;
    }

    public function getStmtimestamp(): ?\DateTimeInterface
    {
        return $this->stmtimestamp;
    }

    public function setStmtimestamp(\DateTimeInterface $stmtimestamp): self
    {
        $this->stmtimestamp = $stmtimestamp;

        return $this;
    }

    public function getPriceGbp(): ?string
    {
        return $this->price_gbp;
    }

    public function setPriceGbp(?string $price_gbp): self
    {
        $this->price_gbp = $price_gbp;

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

}
