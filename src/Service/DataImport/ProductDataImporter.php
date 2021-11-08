<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\DTO\csv\Product as ProductDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductDataImporter
{
    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    private ManagerRegistry $registry;

    private ProductDataCreator $productDataCreator;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        ProductDataCreator $productDataCreator
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->registry = $registry;
        $this->productDataCreator = $productDataCreator;
    }

    public function import(ProductDto $productDto, bool $testMode = null): void
    {
        if (!$productDto->getStock()) {
            $productDto->setStock(0);
        }
        if (!$productDto->getCostInGBP()) {
            $productDto->setCostInGBP(0);
        }

        $violationList = $this->validator->validate($productDto);
        if ($violationList->count() > 0) {
            throw new ValidationFailedException('Failed validation.', $violationList);
        }

        if (!$this->entityManager->isOpen()) {
            $this->registry->resetManager();
        }

        $productData = $this->productDataCreator->createFromImportingData($productDto);

        if (!$testMode) {
            $this->entityManager->persist($productData);
            $this->entityManager->flush();
        }
    }
}
