<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use League\Flysystem\Filesystem;
use Nette\PhpGenerator\PhpNamespace;

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
