<?php

declare(strict_types=1);

namespace App\Tests\database;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class CurrencyTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testWriteCurrencyInDataBase()
    {
        $currencyItem = new Currency();
        $currencyItem->setCode('TestCode1');
        $currencyItem->setDescription('TestDescription1');

        $this->entityManager->persist($currencyItem);
        $this->entityManager->flush();

        $currencyRepository = $this->entityManager->getRepository(Currency::class);
        /**
         * @var Currency $currencyRecord
         */
        $currencyRecord = $currencyRepository->findOneBy(['code' => 'TestCode1']);

        $this->assertEquals('TestCode1', $currencyRecord->getCode());
        $this->assertEquals('TestDescription1', $currencyRecord->getDescription());
    }

    public function testDeleteCurrencyFromDataBase()
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
        $currencyItem = new Currency();
        $currencyItem->setCode('TestCode1');
        $currencyItem->setDescription('TestDescription1');

        $this->entityManager->persist($currencyItem);
        $this->entityManager->flush();

        $currencyRepository = $this->entityManager->getRepository(Currency::class);
        /**
         * @var Currency $currencyRecord
         */
        $currencyRecord = $currencyRepository->findOneBy(['code' => 'TestCode1']);

        $this->entityManager->remove($currencyRecord);
        $this->entityManager->flush();

        $currencyRecord = $currencyRepository->findOneBy(['code' => 'TestCode1']);
        $this->assertEquals(null, $currencyRecord);
    }

    public function testErrorCurrencyInDataBase()
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }

        $currencyItem = new Currency();
        $currencyItem->setCode('TestCode1');
        $currencyItem->setDescription('TestDescription1');

        $this->entityManager->persist($currencyItem);
        $this->entityManager->flush();

        $this->entityManager->close();
        $this->entityManager = $this->entityManager->create(
            $this->entityManager->getConnection(),
            $this->entityManager->getConfiguration()
        );

        $this->entityManager->persist($currencyItem);
        $this->expectException(\Doctrine\DBAL\Exception\UniqueConstraintViolationException::class);
        $this->entityManager->flush();
    }

}
