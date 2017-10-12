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
     * @var string
     */
    private $schemaDraftFileLocation;

    /**
     * @param Filesystem $outputDir
     * @param CodeCreator $codeCreator
     * @param string $schemaDraftFileLocation
     */
    public function __construct(Filesystem $outputDir, CodeCreator $codeCreator, $schemaDraftFileLocation)
    {
        $this->outputDir = $outputDir;
        $this->codeCreator = $codeCreator;
        $this->schemaDraftFileLocation = $schemaDraftFileLocation;
    }

    /**
     * @param $rawSchema
     * @throws InvalidJsonException
     * @throws InvalidSchemaException
     */
    public function generate($rawSchema)
    {
        $schema = json_decode($rawSchema);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException('JSON Schema is invalid JSON.');
        }
        $validator = new Validator();
        $validator->validate($schema, (object)['$ref' => 'file://' . realpath($this->schemaDraftFileLocation)]);

        if (!$validator->isValid()) {
            throw new InvalidSchemaException('JSON Schema is invalid JSON.');
        }
        $classes = $this->codeCreator->create($schema);
        foreach ($classes as $className => $class) {
            $this->outputDir->write($className . '.php', "<?php\n\n" . $class);
        }
    }
}
