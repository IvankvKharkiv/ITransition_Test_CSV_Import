<?php

declare(strict_types=1);

namespace App\Helpers\Savers;

use App\Entity\ProductData;
use Doctrine\ORM\EntityManagerInterface;

class ProductCsvSaver
{
    private EntityManagerInterface $em;
    private \DateTime $currentDate;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->currentDate = new \DateTime('now', new \DateTimeZone('GMT'));
    }

    /**
     * Validates and saves the given in array data to product table.
     *
     * @param array $productData Array of data related to one product
     *
     * @return array<string, int>|null
     */
    public function save(array $productData)
    {
        $validation = \App\Helpers\Validators\ProductCsvValidator::validate($productData);
        if ($validation === null) {
            if (!$this->em->isOpen()) {
                $this->em = $this->em->create(
                    $this->em->getConnection(),
                    $this->em->getConfiguration()
                );
            }

            $product = new ProductData();

            $product->setProductCode($productData['Product Code']);
            $product->setName($productData['Product Name']);
            $product->setDescription($productData['Product Description']);
            $product->setStock(($productData['Stock'] ?? null) !== '' ? intval($productData['Stock']) : null);
            $product->setPriceGbp($productData['Cost in GBP'] ? floatval($productData['Cost in GBP']) : null);
            $product->setDiscontinued(($productData['Discontinued'] ?? '') === 'yes' ? $this->currentDate : null);
            $product->setTimestamp($this->currentDate);
            $this->em->persist($product);

            try {
                $this->em->flush();
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $productData['error'] = 'Dublicated product code error. ';

                return $productData;
            } catch (\Exception $e) {
                $productData['error'] = 'Unknown database error. See server logs for details. ';

                return $productData;
            }

            return null;
        }

        return $validation;
    }
}
