<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\ProductData;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * @internal
 * @coversNothing
 */
class ImportCommandTest extends KernelTestCase
{
    private $arrData = [
        0 => [
            'Product Code' => 'P0001',
            'Product Name' => 'TV',
            'Product Description' => '32” Tv',
            'Stock' => '42342',
            'Cost in GBP' => '399.99',
            'Discontinued' => '',
        ],
        1 => [
            'Product Code' => 'P0002',
            'Product Name' => 'Cd Player',
            'Product Description' => 'Nice CD player',
            'Stock' => '11',
            'Cost in GBP' => '50.12',
            'Discontinued' => 'yes',
        ],
        2 => [
            'Product Code' => 'P0003',
            'Product Name' => 'VCR',
            'Product Description' => 'Top notch VCR',
            'Stock' => '12',
            'Cost in GBP' => '',
            'Discontinued' => 'yes',
        ],
        3 => [
            'Product Code' => 'P0004',
            'Product Name' => 'Bluray Player',
            'Product Description' => 'Watch it in HD',
            'Stock' => '1',
            'Cost in GBP' => '24.55',
            'Discontinued' => '',
        ],
        4 => [
            'Product Code' => 'P0005',
            'Product Name' => 'XBOX360',
            'Product Description' => 'Best.console.ever',
            'Stock' => '5',
            'Cost in GBP' => '30.44',
            'Discontinued' => '',
        ],
        5 => [
            'Product Code' => 'P0006',
            'Product Name' => 'PS3',
            'Product Description' => 'Mind your details',
            'Stock' => '3',
            'Cost in GBP' => '24.99',
            'Discontinued' => '',
        ],
        6 => [
            'Product Code' => 'P0007',
            'Product Name' => '24” Monitor',
            'Product Description' => 'Awesome',
            'Stock' => '',
            'Cost in GBP' => '35.99',
            'Discontinued' => '',
        ],
        7 => [
            'Product Code' => 'P0008',
            'Product Name' => 'CPU',
            'Product Description' => 'Speedy',
            'Stock' => '12',
            'Cost in GBP' => '25.43',
            'Discontinued' => '',
        ],
        8 => [
            'Product Code' => 'P0009',
            'Product Name' => 'Harddisk',
            'Product Description' => 'Great for storing data',
            'Stock' => '0',
            'Cost in GBP' => '99.99',
            'Discontinued' => '',
        ],
        9 => [
            'Product Code' => 'P0010',
            'Product Name' => 'CD Bundle',
            'Product Description' => 'Lots of fun',
            'Stock' => '0',
            'Cost in GBP' => '10',
            'Discontinued' => '',
        ],
        10 => [
            'Product Code' => 'P0011',
            'Product Name' => 'Misc Cables',
            'Product Description' => 'error in export',
            'Stock' => '',
            'Cost in GBP' => '',
            'Discontinued' => '',
        ],
        11 => [
            'Product Code' => 'P0012',
            'Product Name' => 'TV',
            'Product Description' => 'HD ready',
            'Stock' => '45',
            'Cost in GBP' => '50.55',
            'Discontinued' => '',
        ],
        12 => [
            'Product Code' => 'P0013',
            'Product Name' => 'Cd Player',
            'Product Description' => 'Beats MP3',
            'Stock' => '34',
            'Cost in GBP' => '27.99',
            'Discontinued' => '',
        ],
        13 => [
            'Product Code' => 'P0014',
            'Product Name' => 'VCR',
            'Product Description' => 'VHS rules',
            'Stock' => '3',
            'Cost in GBP' => '23',
            'Discontinued' => 'yes',
        ],
        14 => [
            'Product Code' => 'P0015',
            'Product Name' => 'Bluray Player1',
            'Product Description' => 'Excellent picture1',
            'Stock' => '32',
            'Cost in GBP' => '$4.33',
            'Discontinued' => '',
        ],
        15 => [
            'Product Code' => 'P0015',
            'Product Name' => 'Bluray Player2',
            'Product Description' => 'Excellent picture2',
            'Stock' => '32',
            'Cost in GBP' => '4.33',
            'Discontinued' => '',
        ],
        16 => [
            'Product Code' => 'P0016',
            'Product Name' => '24” Monitor',
            'Product Description' => 'Visual candy',
            'Stock' => '3',
            'Cost in GBP' => '45',
            'Discontinued' => '',
        ],
        17 => [
            'Product Code' => 'P0017',
            'Product Name' => 'CPU',
            'Product Description' => 'Processing power',
            'Stock' => ' ideal for multimedia',
            'Cost in GBP' => '3.6',
            'Discontinued' => '4.22',
        ],
        18 => [
            'Product Code' => 'P0018',
            'Product Name' => 'Harddisk',
            'Product Description' => 'More storage options',
            'Stock' => '34',
            'Cost in GBP' => '50',
            'Discontinued' => 'yes',
        ],
        19 => [
            'Product Code' => 'P0019',
            'Product Name' => 'CD Bundle',
            'Product Description' => 'Store all your data. Very convenient',
            'Stock' => '23',
            'Cost in GBP' => '3.44',
            'Discontinued' => '',
        ],
        20 => [
            'Product Code' => 'P0020',
            'Product Name' => 'Cd Player',
            'Product Description' => 'Play CD\'s',
            'Stock' => '56',
            'Cost in GBP' => '30',
            'Discontinued' => '',
        ],
        21 => [
            'Product Code' => 'P0021',
            'Product Name' => 'VCR',
            'Product Description' => 'Watch all those retro videos',
            'Stock' => '12',
            'Cost in GBP' => '3.55',
            'Discontinued' => 'yes',
        ],
        22 => [
            'Product Code' => 'P0022',
            'Product Name' => 'Bluray Player',
            'Product Description' => 'The future of home entertainment!',
            'Stock' => '45',
            'Cost in GBP' => '3',
            'Discontinued' => '',
        ],
        23 => [
            'Product Code' => 'P0023',
            'Product Name' => 'XBOX360',
            'Product Description' => 'Amazing',
            'Stock' => '23',
            'Cost in GBP' => '50',
            'Discontinued' => '',
        ],
        24 => [
            'Product Code' => 'P0024',
            'Product Name' => 'PS3',
            'Product Description' => 'Just don\'t go online',
            'Stock' => '22',
            'Cost in GBP' => '24.33',
            'Discontinued' => 'yes',
        ],
        25 => [
            'Product Code' => 'P0025',
            'Product Name' => 'TV',
            'Product Description' => 'Great for television',
            'Stock' => '21',
            'Cost in GBP' => '40',
            'Discontinued' => '',
        ],
        26 => [
            'Product Code' => 'P0026',
            'Product Name' => 'Cd Player',
            'Product Description' => 'A personal favourite',
            'Stock' => '0',
            'Cost in GBP' => '34.55',
            'Discontinued' => '',
        ],
        27 => [
            'Product Code' => 'P0027',
            'Product Name' => 'VCR',
            'Product Description' => 'Plays videos',
            'Stock' => '34',
            'Cost in GBP' => '1200.03',
            'Discontinued' => 'yes',
        ],
        28 => [
            'Product Code' => 'P0028',
            'Product Name' => 'Bluray Player',
            'Product Description' => 'Plays bluray\'s',
            'Stock' => '32',
            'Cost in GBP' => '1100.04',
            'Discontinued' => 'yes',
        ],
        29 => [
            'Product Code' => 'TEST01',
            'Product Name' => 'TestItem',
            'Product Description' => 'TestDescr',
            'Stock' => '9',
            'Cost in GBP' => '3.6',
            'Discontinued' => '',
        ],
    ];

    private $testFileName = __DIR__ . '/../var/testfiles/stockTest.csv';

    private Application $application;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var array|string|string[]|null
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);

        $encoders = [new CsvEncoder()];
        $serializer = new Serializer([], $encoders);

        if (!is_dir(dirname($this->testFileName))) {
            mkdir(dirname($this->testFileName));
        }

        if (file_exists($this->testFileName)) {
            unlink($this->testFileName);
        }

        $result = $serializer->serialize($this->arrData, 'csv');

        if (!file_exists($this->testFileName)) {
            file_put_contents($this->testFileName, $result);
        }
    }

    public function testCorrectCommandExecute()
    {
        $input = new ArrayInput([
            'command' => 'app:product-data:import',
            'filename' => $this->testFileName,
        ]);

        $output = new BufferedOutput();

        $result = $this->application->run($input, $output);

        $outputString = $output->fetch();

        $outputString = preg_replace('/[|]+/', '', $outputString);
        $outputString = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', '', $outputString));

        $this->assertEquals(0, $result);

        return $outputString;
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testPriceLessFiveStockLessTen($outputString)
    {
        $this->assertEquals(
            3,
            substr_count(
                $outputString,
                'Both price must be more that $5 and quantity must be more than 10pcs in stock.'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testPriceGreaterZero($outputString)
    {
        $this->assertEquals(
            3,
            substr_count(
                $outputString,
                'This value should be greater than 0. Property: costInGBP'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testPriceLessThen1000Doll($outputString)
    {
        $this->assertEquals(
            2,
            substr_count(
                $outputString,
                'This value should be less than 1000$. Property: costInGBP'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testPriceOfTypeFloat($outputString)
    {
        $this->assertEquals(
            1,
            substr_count(
                $outputString,
                'This value should be of type float. Property: costInGBP'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testStockOfTypeInteger($outputString)
    {
        $this->assertEquals(
            1,
            substr_count(
                $outputString,
                'This value should be of type integer. Property: stock'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testDiscontinuedValidation($outputString)
    {
        $this->assertEquals(
            1,
            substr_count(
                $outputString,
                'This value is not valid. Property: discontinued'
            )
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testItemsProcessed($outputString)
    {
        $this->assertEquals(
            30,
            intval(substr(
                $outputString,
                strpos($outputString, 'Items processed = ') + strlen('Items processed = '),
                2
            ))
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testItemsScucceeded($outputString)
    {
        $this->assertEquals(
            23,
            intval(substr(
                $outputString,
                strpos($outputString, 'Items succeeded = ') + strlen('Items succeeded = '),
                2
            ))
        );
    }

    /**
     * @depends testCorrectCommandExecute
     *
     * @param mixed $outputString
     */
    public function testItemsSkipped($outputString)
    {
        $this->assertEquals(
            7,
            intval(substr(
                $outputString,
                strpos($outputString, 'Items skipped = ') + strlen('Items skipped = '),
                2
            ))
        );
    }

    public function testWrongFileType()
    {
        $input = new ArrayInput([
            'command' => 'app:product-data:import',
            'filename' => $this->testFileName,
            '--filetype' => 'dcsv',
        ]);

        $output = new BufferedOutput();

        $result = $this->application->run($input, $output);

        $this->assertNotEquals(0, $result);
        $this->assertNotEquals(false, stripos($output->fetch(), 'Error'));
    }

    public function testTestMode()
    {
        $input = new ArrayInput([
            'command' => 'app:product-data:import',
            'filename' => $this->testFileName,
            '--test' => true,
        ]);

        $output = new BufferedOutput();

        $this->application->run($input, $output);

        $this->assertNotEquals(false, stripos($output->fetch(), 'Running in test mode no data will be saved in DB.'));

        $productRepository = $this->entityManager->getRepository(ProductData::class);

        $product = $productRepository->findOneBy(['productCode' => 'P0001']);

        $this->assertEquals(null, $product);

    }


    public function tearDown(): void
    {
        if(file_exists($this->testFileName)){
            unlink($this->testFileName);
        }
        parent::tearDown();
    }
}
