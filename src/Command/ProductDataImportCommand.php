<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\csv\Product as CsvDtoProduct;
use App\Service\DataImport\ProductDataImporter;
use Symfony\Component\Console\Command\Command;
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

    public static array $availableFileTypes = [
        self::CSV_FILE_TYPE,
    ];

    protected static $defaultName = 'app:product-data:import';
    private string $fileTypeList = '';
    private SerializerInterface $serializer;
    private ProductDataImporter $productDataImporter;

    public function __construct(
        SerializerInterface $serializer,
        ProductDataImporter $productDataImporter
    ) {
        $this->serializer = $serializer;
        $this->productDataImporter = $productDataImporter;
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

        if (in_array($input->getOption('filetype'), self::$availableFileTypes)) {
            $content = file_get_contents($input->getArgument('filename'));

            $productDtoArr = $this->serializer->deserialize(
                $content,
                CsvDtoProduct::class . '[]',
                $input->getOption('filetype')
            );

            foreach ($productDtoArr as $productDtoItem) {
                $itemsProcessed++;

                try {
                    $productData = null;
                    $productData = $this->productDataImporter->import($productDtoItem, $input->getOption('test'));
                    if ($productData) {
                        $itemsSucceeded++;
                    }
                } catch (ValidationFailedException $e) {
                    $itemsSkipped++;
                    $validationErrorList[] = $e;
                } catch (\Exception $e) {
                    $itemsSkipped++;
                    $difErrorList[] = [$e->getMessage()];
                }
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

        $finalErrorList = [];
        foreach ($validationErrorList as $validationError) {
            foreach ($validationError->getViolations() as $violation) {
                $finalErrorList[] = [
                    $violation->getRoot()->getProductCode() . ' ' . $violation->getRoot()->getName(),
                    wordwrap($violation->getMessage() . ' Property: ' . $violation->getPropertyPath()),
                ];
            }
        }

        $io->table(['Product', 'Validation Errors'], $finalErrorList);
        $io->table(['Other Errors'], $difErrorList);

        $io->info([
            'Items processed = ' . $itemsProcessed,
            'Items succeeded = ' . $itemsSucceeded,
            'Items skipped = ' . $itemsSkipped,
        ]);

        return Command::SUCCESS;
    }
}
