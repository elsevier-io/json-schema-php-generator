<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use League\Flysystem\Filesystem;
use Nette\PhpGenerator\PhpNamespace;
use JsonSchema\Validator;

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
        if (!isset($schema->definitions)) {
            return;
        }
        foreach ($schema->definitions as $name => $definition) {
            $namespace = new PhpNamespace('Elsevier\JSONSchemaPHPGenerator\Examples');
            $class = $namespace->addClass($name);
            $class->addImplement('\JsonSerializable');
            $constructor = $class->addMethod('__construct');
            $constructorComment = '';
            $constructorBody = '';
            $serializableArrayBody = '';
            foreach ($definition->properties as $propertyName => $propertyAttributes) {
                $class->addProperty($propertyName)
                    ->setVisibility('private')
                    ->addComment('@var int');
                $constructor->addParameter($propertyName);
                $constructorComment.= "@param int \$$propertyName";
                $constructorBody.= '$this->' . $propertyName . ' = $' . $propertyName . ';' . "\n";
                $serializableArrayBody.= "'" . $propertyName . "'=>" . '$this->' . $propertyName . ",\n";
            }
            $constructor->addComment($constructorComment)
                ->addBody($constructorBody);
            $serializableArray = 'return [' . $serializableArrayBody . '];';
            $class->addMethod('jsonSerialize')
                ->addBody($serializableArray);
            $this->outputDir->write($name . '.php', "<?php\n\n" . $namespace);
        }
    }
}
