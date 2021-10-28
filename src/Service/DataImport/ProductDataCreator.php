<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\DTO\csv\Product as ProductDto;
use App\Entity\Currency;
use App\Entity\ProductData;
use App\Entity\ProductPrice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductDataCreator
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function createFromImportingData(ProductDto $productDto): ProductData
    {
        try {
            $currentDate = new \DateTime('now', new \DateTimeZone('GMT'));
            if ($productDto->discontinued) {
                $productDto->setDiscontinued($currentDate->getTimestamp());
            } else {
                $productDto->setDiscontinued(null);
            }
            /**
             * @var ProductData $productData
             */
            $productData = $this->serializer->denormalize($productDto, ProductData::class);

            if ($productDto->getCostInGBP()) {
                $productPrice = new ProductPrice();
                $productPrice->setPrice($productDto->getCostInGBP());

                $currencyRep = $this->em->getRepository('App\\Entity\\Currency');
                $currency = $currencyRep->findOneBy(['code' => 'GBP']);
                if (!$currency) {
                    $currency = new Currency();
                    $currency->setCode('GBP');
                    $currency->setDescription('Great Britain Pound');
                    $this->em->persist($currency);
                    $this->em->flush();
                }

                $productPrice->setCurrency($currency);
                $productData->addProductPrice($productPrice);
            }

            return $productData;
        } catch (\Exception $e) {
            throw new \LogicException('It always must be possible to create product data from importing one. Current error: ' . $e->getMessage());
        }
    }
}
