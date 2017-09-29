<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use League\Flysystem\Filesystem;

class Generator
{
    /**
     * @var Filesystem
     */
    private $outputDir;

    /**
     * @param Filesystem $outputDir
     */
    public function __construct(Filesystem $outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * @param $rawSchema
     * @throws InvalidSchemaException
     */
    public function generate($rawSchema) {
        $schema = json_decode($rawSchema);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidSchemaException('JSON Schema is invalid JSON.');
        }
        if (!isset($schema->definitions)) {
            return;
        }
        foreach ($schema->definitions as $name => $definition) {
            $this->outputDir->write($name . '.php', '');
        }
    }
}