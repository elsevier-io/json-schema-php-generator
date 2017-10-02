<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use League\Flysystem\Filesystem;
use JsonSchema\Validator;

class Generator
{
    /**
     * @var Filesystem
     */
    private $outputDir;
    /**
     * @var CodeCreator
     */
    private $codeCreator;

    /**
     * @param Filesystem $outputDir
     */
    public function __construct(Filesystem $outputDir)
    {
        $this->outputDir = $outputDir;
        $this->codeCreator = new CodeCreator();
    }

    /**
     * @param $rawSchema
     * @throws InvalidJsonException
     * @throws InvalidSchemaException
     */
    public function generate($rawSchema) {
        $schema = json_decode($rawSchema);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException('JSON Schema is invalid JSON.');
        }
        $validator = new Validator();
        $schemaDraft4 = __DIR__ . '/../vendor/justinrainbow/json-schema/dist/schema/json-schema-draft-04.json';
        $validator->validate($schema, (object)['$ref' => 'file://' . realpath($schemaDraft4)]);

        if (!$validator->isValid()) {
            throw new InvalidSchemaException('JSON Schema is invalid JSON.');
        }
        $classes = $this->codeCreator->create($schema);
        foreach ($classes as $className => $class) {
            $this->outputDir->write($className . '.php', "<?php\n\n" . $class);
        }
    }
}
