<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\csv\Product as CsvDtoProduct;
use App\Service\DataImport\ProductDataImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table as SymfonyConsoleTable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ProductDataImportCommand extends Command
{
    public const CSV_FILE_TYPE = 'csv';

    public const SUPPORTED_FORMATS = [
        self::CSV_FILE_TYPE,
    ];

    protected static $defaultName = 'app:product-data:import';

    private SerializerInterface $serializer;

    private ProductDataImporter $productDataImporter;

    public function __construct(
        SerializerInterface $serializer,
        ProductDataImporter $productDataImporter
    ) {
        $this->serializer = $serializer;
        $this->productDataImporter = $productDataImporter;
        parent::__construct();
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
            'Specify file type. Available file types: ' . implode(', ', self::SUPPORTED_FORMATS),
            'csv'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $validationErrorList = [];
        $difErrorList = [];
        $io = new SymfonyStyle($input, $output);

        $itemsProcessed = 0;
        $itemsSucceeded = 0;
        $itemsSkipped = 0;

        if ($input->getOption('test')) {
            $io->warning('Running in test mode no data will be saved in DB.');
        }

        if (!in_array($input->getOption('filetype'), self::SUPPORTED_FORMATS)) {
            $io->error(
                'Available filetypes are: ' .
                implode(', ', self::SUPPORTED_FORMATS) .
                '. Given value is: "' .
                $input->getOption('filetype') . '"'
            );

            return Command::FAILURE;
        }

        $content = file_get_contents($input->getArgument('filename'));

        $productDto = $this->serializer->deserialize(
            $content,
            CsvDtoProduct::class . '[]',
            $input->getOption('filetype')
        );

        $validationErrorTable = new SymfonyConsoleTable($output);
        $validationErrorTable
            ->setHeaderTitle('Validation Errors')
            ->setHeaders([['Product', 'Validation Errors']])
            ->setFooterTitle('End of Validation Error Table');

        $otherErrorTable = new SymfonyConsoleTable($output);
        $otherErrorTable
            ->setHeaderTitle('Other errors')
            ->setHeaders(['Other errors'])
            ->setFooterTitle('End of other Error Table');

        foreach ($productDto as $productDtoItem) {
            $itemsProcessed++;

            try {
                $this->productDataImporter->import($productDtoItem, $input->getOption('test'));
                $itemsSucceeded++;
            } catch (ValidationFailedException $e) {
                $itemsSkipped++;
                $validationErrorList[] = $e;

                foreach ($e->getViolations() as $violation) {
                    $validationErrorTable->addRow([
                        $violation->getRoot()->getProductCode() . ' ' . $violation->getRoot()->getName(),
                        wordwrap($violation->getMessage() . ' Property: ' . $violation->getPropertyPath()),
                    ]);
                }
            } catch (\Exception $e) {
                $itemsSkipped++;
                $difErrorList[] = [$e->getMessage()];

                $otherErrorTable->addRow([$e->getMessage()]);
            }
        }

        $validationErrorTable->render();
        $otherErrorTable->render();

        $io->info([
            'Items processed = ' . $itemsProcessed,
            'Items succeeded = ' . $itemsSucceeded,
            'Items skipped = ' . $itemsSkipped,
        ]);

        return Command::SUCCESS;
    }
}
