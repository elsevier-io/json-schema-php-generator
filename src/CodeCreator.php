<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use Nette\PhpGenerator\PhpNamespace;

class CodeCreator
{
    /**
     * @var string
     */
    private $defaultNamespace;

    /**
     * @param string $defaultNamespace
     */
    public function __construct($defaultNamespace)
    {
        $this->defaultNamespace = $defaultNamespace;
    }

    /**
     * @param string $schema - A valid JSON Schema
     * @return PhpNamespace[]
     */
    public function create($schema) {
        if (!isset($schema->definitions)) {
            return [];
        }
        $classes = [];
        foreach ($schema->definitions as $name => $definition) {
            $namespace = new PhpNamespace($this->defaultNamespace);
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
            $classes[$name] = $namespace;
        }
        return $classes;
    }
}