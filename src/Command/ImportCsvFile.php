<?php

namespace App\Command;

use App\Helpers\Savers\ProductCsvSaver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Tblproductdata;
use Doctrine\ORM\EntityManagerInterface;




class ImportCsvFile extends Command
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-csv-file';

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Full path to .csv file which needs to be parsed.');
        $this->addOption('test', 't', null, 'Run command in test mode without saving data to DB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $csvEncoder = new CsvEncoder();
        $productCsvSaver = new ProductCsvSaver($this->em);


        $content = file_get_contents($input->getArgument('filename'));
        $result = $csvEncoder->decode($content, 'csv');



        $itemsProcessed = 0;
        $itemsSucceeded = 0;
        $itemsSkipped = 0;


        if ($input->getOption('test')) {
            $output->writeln("\n\n\nRunning in test mode no data will be saved in DB.\n\n\n");
        }

        foreach ($result as $item){
            $itemsProcessed ++;
            if ($input->getOption('test')) {
                $saveResult = \App\Helpers\Validators\ProductCsvValidator::validate($item);
                if ($saveResult) {
                    $itemsSkipped++;
                    $output->writeln('Item Product_Code = ' .
                        ($item['Product Code'] ?? '') . ' Product_Name = ' .
                        ($item['Product Name'] ?? '') . ' Product_Description = ' .
                        ($item['Product Description'] ?? '') .
                        ' Was not saved to database because of the next errors: ' .
                        $saveResult['error'] . "\n\n" );
                } else {
                    $itemsSucceeded++;
                }

            } else {
                $saveResult = $productCsvSaver->save($item);
                if ($saveResult) {
                    $itemsSkipped++;
                    $output->writeln('Item Product_Code = ' .
                        ($item['Product Code'] ?? '') .
                        ' Product_Name = ' .
                        ($item['Product Name'] ?? '') .
                        ' Product_Description = ' .
                        ($item['Product Description'] ?? '') .
                        ' Was not saved to database because of the next errors: ' .
                        $saveResult['error'] );
                } else {
                    $itemsSucceeded++;
                }
            }

        }

        $output->writeln('Items processed = ' . $itemsProcessed);
        $output->writeln('Items succeeded = ' . $itemsSucceeded);
        $output->writeln('Items skipped = ' . $itemsSkipped);

        $output->writeln('Success');
        return Command::SUCCESS;
    }

}