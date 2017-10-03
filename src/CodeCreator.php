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
                $jsonPropertyType = isset($propertyAttributes->type) ? $propertyAttributes->type : 'number';
                switch ($jsonPropertyType) {
                    case 'number':
                        $propertyType = 'integer';
                        break;
                    case 'boolean':
                    case 'string':
                    default:
                        $propertyType = $jsonPropertyType;
                        break;
                }
                if (isset($propertyAttributes->enum)) {
                    if (count($propertyAttributes->enum) > 1) {
                        $propertyType = $name . ucfirst($propertyName);
                        $classes[$propertyType] = $this->createEnum($propertyType, $propertyAttributes->enum);
                        $classes['InvalidValueException'] = 'InvalidValueException';
                        $constructorBody.= '$this->' . $propertyName . ' = $' . $propertyName . '->getValue();' . "\n";
                        $constructor->addParameter($propertyName)
                            ->setTypeHint($this->defaultNamespace . '\\' . $propertyType);
                        $constructorComment.= "@param $propertyType \$$propertyName";
                    } else {
                        $constructorBody.= '$this->' . $propertyName . " = '" . $propertyAttributes->enum[0] . "';\n";
                    }
                } else {
                    $constructorBody.= '$this->' . $propertyName . ' = $' . $propertyName . ';' . "\n";
                    $constructor->addParameter($propertyName);
                    $constructorComment.= "@param $propertyType \$$propertyName";
                }
                $class->addProperty($propertyName)
                    ->setVisibility('private')
                    ->addComment("@var $propertyType");
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

    private function createEnum($name, $values) {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($name);
        foreach ($values as $value) {
            $class->addConst(ucfirst($value), $value);
        }
        $class->addProperty('value')
            ->setVisibility('private')
            ->addComment("@var string");
        $constructor = $class->addMethod('__construct');
        $constructor->addParameter('value');
        $constructorComment = "@param \$value\n";
        $constructorComment.= "@throws InvalidValueException";
        $constructor->addComment($constructorComment);
        $constants = array_map(function ($constant) {
            return 'self::' . ucfirst($constant);
        }, $values);
        $constructorBody = ' $possibleValues = [' . implode(', ', $constants) . '];
            if (!in_array($value, $possibleValues)) {
                throw new InvalidValueException($value . \' is not an allowed value for EnumPropertyFoo\');
            }
            $this->value = $value;';
        $constructor->addBody($constructorBody);
        $class->addMethod('getValue')
            ->addBody(' return $this->value;');
        return $namespace;
    }
}