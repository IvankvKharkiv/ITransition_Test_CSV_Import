<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tblproductdata;


class TestPhpFixer
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var \DateTime
     */
    private $currentDate;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; $this->currentDate = new \DateTime('now', new \DateTimeZone('GMT'));
    }


    /**
     * Validates and saves the given in array data to product table.
     *
     * @param array $productData Array of data related to one product
     *
     * @return array<string, int>|null
     */
    public function save (array $productData) {
        $validation = \App\Helpers\Validators\ProductCsvValidator::validate($productData);
        if ($validation == null){

            if (!$this->em->isOpen()) {
                $this->em = $this->em->create(
                    $this->em->getConnection(),
                    $this->em->getConfiguration()
                );
            }

            $product = new Tblproductdata();





            $product->setStrproductcode($productData['Product Code']); $product->setStrproductname($productData['Product Name']);
            $product->setStrproductdesc($productData['Product Description']);
            $product->setStock( ($productData['Stock'] ?? null) != '' ? $productData['Stock'] : null);
            $product->setPriceGbp( $productData['Cost in GBP'] ?? null);
            $product->setDtmdiscontinued(($productData['Discontinued'] ?? '') == 'yes' ? $this->currentDate : null);
            $product->setStmtimestamp($this->currentDate);
            $this->em->persist($product);
            try {$this->em->flush();}catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $productData['error'] = 'Dublicated product code error. ';
                return $productData;
            }catch(\Exception $e) {
            $productData['error'] = 'Unknown database error. See server logs for details. ';
            return $productData;
            }
            return null;
        } else {
            return $validation;
        }
    }




}