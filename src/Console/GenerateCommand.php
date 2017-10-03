<?php

namespace Elsevier\JSONSchemaPHPGenerator\Console;

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

class GenerateCommand extends Command {

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
        $outputDir = $input->getOption('outputDir');
        $outputDirFiles = new Local($outputDir);
        $outputDir = new Filesystem($outputDirFiles);
        $codeCreator = new CodeCreator($input->getArgument('class'), $input->getArgument('namespace'));
        $generator = new Generator($outputDir, $codeCreator);
        $localFiles = new Local('.');
        $schemaDir = new Filesystem($localFiles);
        $jsonSchema = $schemaDir->read($input->getArgument('schema'));
        $generator->generate($jsonSchema);
    }
}
