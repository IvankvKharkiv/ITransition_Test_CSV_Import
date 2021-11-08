<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\DTO\csv\Product as ProductDto;
use App\Entity\ProductData;
use App\Entity\ProductPrice;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductDataCreator
{
    private SerializerInterface $serializer;

    private CurrencyRepository $currencyRepository;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function createFromImportingData(ProductDto $productDto): ProductData
    {
        $currentDate = new \DateTime('now', new \DateTimeZone('GMT'));
        if ($productDto->discontinued) {
            $productDto->setDiscontinued($currentDate->format(\DateTimeInterface::ATOM));
        } else {
            $productDto->setDiscontinued(null);
        }
        /**
         * @var ProductData $productData
         */
        $productData = $this->serializer->denormalize($productDto, ProductData::class);

        if ($productDto->getCostInGBP()) {
            $currency = $this->currencyRepository->findOneByCode('GBP');
            $productPrice = new ProductPrice($productData, $currency, $productDto->getCostInGBP());
            $productData->addProductPrice($productPrice);
        }

        return $productData;
    }
}
