<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Console\Input\ArrayInput;


class ImportCommandTest extends KernelTestCase

{
    private $arrData = array (
  0 =>
  array (
    'Product Code' => 'P0001',
    'Product Name' => 'TV',
    'Product Description' => '32” Tv',
    'Stock' => '42342',
    'Cost in GBP' => '399.99',
    'Discontinued' => '',
  ),
  1 =>
  array (
    'Product Code' => 'P0002',
    'Product Name' => 'Cd Player',
    'Product Description' => 'Nice CD player',
    'Stock' => '11',
    'Cost in GBP' => '50.12',
    'Discontinued' => 'yes',
  ),
  2 =>
  array (
    'Product Code' => 'P0003',
    'Product Name' => 'VCR',
    'Product Description' => 'Top notch VCR',
    'Stock' => '12',
    'Cost in GBP' => '39.33',
    'Discontinued' => 'yes',
  ),
  3 =>
  array (
    'Product Code' => 'P0004',
    'Product Name' => 'Bluray Player',
    'Product Description' => 'Watch it in HD',
    'Stock' => '1',
    'Cost in GBP' => '24.55',
    'Discontinued' => '',
  ),
  4 =>
  array (
    'Product Code' => 'P0005',
    'Product Name' => 'XBOX360',
    'Product Description' => 'Best.console.ever',
    'Stock' => '5',
    'Cost in GBP' => '30.44',
    'Discontinued' => '',
  ),
  5 =>
  array (
    'Product Code' => 'P0006',
    'Product Name' => 'PS3',
    'Product Description' => 'Mind your details',
    'Stock' => '3',
    'Cost in GBP' => '24.99',
    'Discontinued' => '',
  ),
  6 =>
  array (
    'Product Code' => 'P0007',
    'Product Name' => '24” Monitor',
    'Product Description' => 'Awesome',
    'Stock' => '',
    'Cost in GBP' => '35.99',
    'Discontinued' => '',
  ),
  7 =>
  array (
    'Product Code' => 'P0008',
    'Product Name' => 'CPU',
    'Product Description' => 'Speedy',
    'Stock' => '12',
    'Cost in GBP' => '25.43',
    'Discontinued' => '',
  ),
  8 =>
  array (
    'Product Code' => 'P0009',
    'Product Name' => 'Harddisk',
    'Product Description' => 'Great for storing data',
    'Stock' => '0',
    'Cost in GBP' => '99.99',
    'Discontinued' => '',
  ),
  9 =>
  array (
    'Product Code' => 'P0010',
    'Product Name' => 'CD Bundle',
    'Product Description' => 'Lots of fun',
    'Stock' => '0',
    'Cost in GBP' => '10',
    'Discontinued' => '',
  ),
  10 =>
  array (
    'Product Code' => 'P0011',
    'Product Name' => 'Misc Cables',
    'Product Description' => 'error in export',
    'Stock' => '',
    'Cost in GBP' => '',
    'Discontinued' => '',
  ),
  11 =>
  array (
    'Product Code' => 'P0012',
    'Product Name' => 'TV',
    'Product Description' => 'HD ready',
    'Stock' => '45',
    'Cost in GBP' => '50.55',
    'Discontinued' => '',
  ),
  12 =>
  array (
    'Product Code' => 'P0013',
    'Product Name' => 'Cd Player',
    'Product Description' => 'Beats MP3',
    'Stock' => '34',
    'Cost in GBP' => '27.99',
    'Discontinued' => '',
  ),
  13 =>
  array (
    'Product Code' => 'P0014',
    'Product Name' => 'VCR',
    'Product Description' => 'VHS rules',
    'Stock' => '3',
    'Cost in GBP' => '23',
    'Discontinued' => 'yes',
  ),
  14 =>
  array (
    'Product Code' => 'P0015',
    'Product Name' => 'Bluray Player',
    'Product Description' => 'Excellent picture',
    'Stock' => '32',
    'Cost in GBP' => '$4.33',
    'Discontinued' => '',
  ),
  15 =>
  array (
    'Product Code' => 'P0015',
    'Product Name' => 'Bluray Player',
    'Product Description' => 'Excellent picture',
    'Stock' => '32',
    'Cost in GBP' => '4.33',
    'Discontinued' => '',
  ),
  16 =>
  array (
    'Product Code' => 'P0016',
    'Product Name' => '24” Monitor',
    'Product Description' => 'Visual candy',
    'Stock' => '3',
    'Cost in GBP' => '45',
    'Discontinued' => '',
  ),
  17 =>
  array (
    'Product Code' => 'P0017',
    'Product Name' => 'CPU',
    'Product Description' => 'Processing power',
    'Stock' => ' ideal for multimedia',
    'Cost in GBP' => '4',
    'Discontinued' => '4.22',
  ),
  18 =>
  array (
    'Product Code' => 'P0018',
    'Product Name' => 'Harddisk',
    'Product Description' => 'More storage options',
    'Stock' => '34',
    'Cost in GBP' => '50',
    'Discontinued' => 'yes',
  ),
  19 =>
  array (
    'Product Code' => 'P0019',
    'Product Name' => 'CD Bundle',
    'Product Description' => 'Store all your data. Very convenient',
    'Stock' => '23',
    'Cost in GBP' => '3.44',
    'Discontinued' => '',
  ),
  20 =>
  array (
    'Product Code' => 'P0020',
    'Product Name' => 'Cd Player',
    'Product Description' => 'Play CD\'s',
    'Stock' => '56',
    'Cost in GBP' => '30',
    'Discontinued' => '',
  ),
  21 =>
  array (
    'Product Code' => 'P0021',
    'Product Name' => 'VCR',
    'Product Description' => 'Watch all those retro videos',
    'Stock' => '12',
    'Cost in GBP' => '3.55',
    'Discontinued' => 'yes',
  ),
  22 =>
  array (
    'Product Code' => 'P0022',
    'Product Name' => 'Bluray Player',
    'Product Description' => 'The future of home entertainment!',
    'Stock' => '45',
    'Cost in GBP' => '3',
    'Discontinued' => '',
  ),
  23 =>
  array (
    'Product Code' => 'P0023',
    'Product Name' => 'XBOX360',
    'Product Description' => 'Amazing',
    'Stock' => '23',
    'Cost in GBP' => '50',
    'Discontinued' => '',
  ),
  24 =>
  array (
    'Product Code' => 'P0024',
    'Product Name' => 'PS3',
    'Product Description' => 'Just don\'t go online',
    'Stock' => '22',
    'Cost in GBP' => '24.33',
    'Discontinued' => 'yes',
  ),
  25 =>
  array (
    'Product Code' => 'P0025',
    'Product Name' => 'TV',
    'Product Description' => 'Great for television',
    'Stock' => '21',
    'Cost in GBP' => '40',
    'Discontinued' => '',
  ),
  26 =>
  array (
    'Product Code' => 'P0026',
    'Product Name' => 'Cd Player',
    'Product Description' => 'A personal favourite',
    'Stock' => '0',
    'Cost in GBP' => '34.55',
    'Discontinued' => '',
  ),
  27 =>
  array (
    'Product Code' => 'P0027',
    'Product Name' => 'VCR',
    'Product Description' => 'Plays videos',
    'Stock' => '34',
    'Cost in GBP' => '1200.03',
    'Discontinued' => 'yes',
  ),
  28 =>
  array (
    'Product Code' => 'P0028',
    'Product Name' => 'Bluray Player',
    'Product Description' => 'Plays bluray\'s',
    'Stock' => '32',
    'Cost in GBP' => '1100.04',
    'Discontinued' => 'yes',
  ),
  29 =>
  array (
    'Product Code' => 'TEST01',
    'Product Name' => 'TestItem',
    'Product Description' => 'TestDescr',
    'Stock' => '9',
    'Cost in GBP' => '4',
    'Discontinued' => '',
  ),
);
    private $testFileName = __DIR__ . '/../var/testfiles/stockTest.csv';
    private Application $application;


    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }


    public function testImportCommand(){

        $encoders = [new CsvEncoder()];
        $serializer = new Serializer([], $encoders);

        if (!is_dir(dirname($this->testFileName))) {
            mkdir(dirname($this->testFileName));
        }

        if(file_exists($this->testFileName)){
            unlink($this->testFileName);
        }


        $result = $serializer->serialize($this->arrData, 'csv');


        if(!file_exists($this->testFileName)){
            file_put_contents($this->testFileName, $result);
        }

        $input = new ArrayInput([
            'command' => 'app:product-data:import',
            // (optional) define the value of command arguments
            'filename' => $this->testFileName,
        ]);

        $output = new BufferedOutput();

        $this->application->run($input, $output);

//        var_dump(substr_count($output->fetch(), 'Both'));


        $number = substr_count($output->fetch(), 'Both');


        $this->assertEquals(6, $number);
//        $this->assertEquals(5, substr_count('asdf123sadf123sadf123','123'));

        $this->assertEquals(true, file_exists($this->testFileName));
    }


}
