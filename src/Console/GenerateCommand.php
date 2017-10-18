<?php

namespace Elsevier\JSONSchemaPHPGenerator\Console;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Elsevier\JSONSchemaPHPGenerator\Generator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class GenerateCommand extends Command
{
    /**
     * @var string
     */
    private $schemaDraftFileLocation;

    public function setSchemaDraftFileLocation($schemaDraftFile)
    {
        $this->schemaDraftFileLocation = $schemaDraftFile;
    }

    protected function configure()
    {
        $this
            ->setName('php:generate-from-json')
            ->setDescription('Generate PHP code from a JSON schema')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('schema', InputArgument::REQUIRED),
                    new InputArgument('class', InputArgument::REQUIRED),
                    new InputArgument('namespace', InputArgument::REQUIRED),
                    new InputOption('outputDir', 'o', InputOption::VALUE_REQUIRED, 'Target dir for output files', './outputDir/'),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputDirPath = $input->getOption('outputDir');
        $output->writeln('Setting output dir to ' . realpath($outputDirPath));
        $outputDirFiles = new Local($outputDirPath);
        $outputDir = new Filesystem($outputDirFiles);

        $output->writeln('Deleting all files in output dir.');
        $files = $outputDir->listContents();
        foreach ($files as $file) {
            $outputDir->delete($file['path']);
        }

        $defaultClass = $input->getArgument('class');
        $defaultNamespace = $input->getArgument('namespace');
        $output->writeln('Generating code in namespace ' . $defaultNamespace);
        $output->writeln('    with top-level class ' . $defaultClass);

        $log = new Logger('JSONSchemaPHPGeneratorLogger');
        $log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
        $codeCreator = new CodeCreator($defaultClass, $defaultNamespace, $log);
        $generator = new Generator($outputDir, $codeCreator, $this->schemaDraftFileLocation);
        $localFiles = new Local('.');
        $schemaDir = new Filesystem($localFiles);

        $schemaPath = $input->getArgument('schema');
        $output->writeln('Using JSON Schema in '. realpath($schemaPath));
        $jsonSchema = $schemaDir->read($schemaPath);
        $generator->generate($jsonSchema);
    }
}
