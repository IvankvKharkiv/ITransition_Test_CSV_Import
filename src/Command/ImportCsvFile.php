<?php

declare(strict_types=1);

namespace App\Command;

use App\Helpers\Savers\ProductCsvSaver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

class ImportCsvFile extends Command
{
    public const CSV_FILE_TYPE = 'CSV';

    public static array $availableFileTypes = [
        self::CSV_FILE_TYPE,
    ];

    protected static $defaultName = 'app:product-data:import';
    private string $fileTypeList = '';
    private ProductCsvSaver $productCsvSaver;
    private SerializerInterface $serializer;

    public function __construct(ProductCsvSaver $productCsvSaver, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->productCsvSaver = $productCsvSaver;
        parent::__construct();
        foreach (self::$availableFileTypes as $fileType) {
            $this->fileTypeList = $this->fileTypeList . ($this->fileTypeList ? ', ' : '') . $fileType;
        }
    }

    protected function configure(): void
    {
        $this->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'Full path to .csv file which needs to be parsed.'
        );
        $this->addOption(
            'test',
            't',
            null,
            'Run command in test mode without saving data to DB.'
        );

        $this->addOption(
            'filetype',
            'ft',
            InputOption::VALUE_REQUIRED,
            'Specify file type. Available file types: ' . $this->fileTypeList,
            'CSV'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (in_array($input->getOption('filetype'), self::$availableFileTypes)) {
            switch ($input->getOption('filetype')) {
                case self::CSV_FILE_TYPE:
                    $content = file_get_contents($input->getArgument('filename'));
                    $result = $this->serializer->decode($content, 'csv');

                    break;
            }
        } else {
            $io->error(
                'Available filetypes are: ' .
                $this->fileTypeList .
                '. Given value is: "' .
                $input->getOption('filetype') . '"'
            );

            return Command::FAILURE;
        }

//        $output->writeln('Parameter filetype = ' . $input->getOption('filetype'));
//        $output->writeln('Parameter test = ' . $input->getOption('test'));

        $itemsProcessed = 0;
        $itemsSucceeded = 0;
        $itemsSkipped = 0;

        if ($input->getOption('test')) {
            $io->warning('Running in test mode no data will be saved in DB.');
        }

        $errorArray = [];

        foreach ($result as $item) {
            $itemsProcessed++;
            if ($input->getOption('test')) {
                $saveResult = \App\Helpers\Validators\ProductCsvValidator::validate($item);
                if ($saveResult) {
                    $itemsSkipped++;
                    $errorArray[] = ['Item Product_Code = ' .
                        ($item['Product Code'] ?? '') . ' Product_Name = ' .
                        ($item['Product Name'] ?? '') . ' Product_Description = ' .
                        ($item['Product Description'] ?? ''),
                        ' Was not saved to database because of the next errors: ' .
                        $saveResult['error'], ];
                } else {
                    $itemsSucceeded++;
                }
            } else {
                $saveResult = $this->productCsvSaver->save($item);
                if ($saveResult) {
                    $itemsSkipped++;
                    $errorArray[] = ['Item Product_Code = ' .
                        ($item['Product Code'] ?? '') .
                        ' Product_Name = ' .
                        ($item['Product Name'] ?? '') .
                        ' Product_Description = ' .
                        ($item['Product Description'] ?? ''),
                        ' Was not saved to database because of the next errors: ' .
                        $saveResult['error'], ];
                } else {
                    $itemsSucceeded++;
                }
            }
        }

        $io->table(['Product', 'Errors'], $errorArray);

        $io->info([
            'Items processed = ' . $itemsProcessed,
            'Items succeeded = ' . $itemsSucceeded,
            'Items skipped = ' . $itemsSkipped,
        ]);

        return Command::SUCCESS;
    }
}
