<?php

namespace Elsevier\JSONSchemaPHPGenerator;

class Generator
{
    /**
     * @var string
     */
    private $outputDir;

    /**
     * Generator constructor.
     * @param string $outputDir
     */
    public function __construct($outputDir)
    {
        $this->outputDir = $outputDir;
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
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
            touch($this->outputDir . DIRECTORY_SEPARATOR . $name . '.php');
        }
    }
}