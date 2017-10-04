<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use Elsevier\JSONSchemaPHPGenerator\Properties\Factory;
use Nette\PhpGenerator\PhpNamespace;

class CodeCreator
{
    /**
     * @var string
     */
    private $defaultClass;
    /**
     * @var string
     */
    private $defaultNamespace;
    /**
     * @var Factory
     */
    private $properties;

    /**
     * @param string $defaultClass
     * @param string $defaultNamespace
     */
    public function __construct($defaultClass, $defaultNamespace)
    {
        $this->defaultClass = $defaultClass;
        $this->defaultNamespace = $defaultNamespace;
        $this->properties = new Factory();
    }

    /**
     * @param string $schema - A valid JSON Schema
     * @return PhpNamespace[]
     */
    public function create($schema) {
        if (!isset($schema->properties)) {
            return [];
        }
        $classes = [];
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($this->defaultClass)
                        ->addImplement('\JsonSerializable');
        $constructor = $class->addMethod('__construct');
        $constructorComment = '';
        $constructorBody = '';
        $serializableArrayBody = '';
        foreach ($schema->properties as $propertyName => $propertyAttributes) {
            $property = $this->properties->create($propertyName, $propertyAttributes);
            if ($property) {
                $constructorBody.= $property->constructorBody();
                $constructorComment.= $property->constructorComment();
                $constructor = $property->addConstructorParameter($constructor);
                $class = $property->addTo($class);
                $serializableArrayBody.= $property->serializingCode();
                continue;
            }
            $jsonPropertyType = isset($propertyAttributes->type) ? $propertyAttributes->type : 'number';
            switch ($jsonPropertyType) {
                case 'boolean':
                default:
                    $propertyType = $jsonPropertyType;
                    break;
            }
            if (isset($propertyAttributes->enum)) {
                if (count($propertyAttributes->enum) > 1) {
                    $propertyType = $this->defaultClass . ucfirst($propertyName);
                    $classes[$propertyType] = $this->createEnum($propertyType, $propertyAttributes->enum);
                    $classes['InvalidValueException'] = $this->createException('InvalidValueException');
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
            $serializableArrayBody.= "    '" . $propertyName . "'=>" . '$this->' . $propertyName . ",\n";
        }
        if (!empty($constructorComment)) {
            $constructor->addComment($constructorComment);
        }
        $constructor->addBody($constructorBody);
        $serializableArray = "return [\n" . $serializableArrayBody . "];";
        $class->addMethod('jsonSerialize')
            ->addBody($serializableArray);
        $classes[$this->defaultClass] = $namespace;
        return $classes;
    }

    /**
     * @param string $className
     * @param array $values
     * @return PhpNamespace
     */
    private function createEnum($className, $values) {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($className);
        foreach ($values as $value) {
            $class->addConst(ucfirst($value), $value);
        }
        $class->addProperty('value')
            ->setVisibility('private')
            ->addComment("@var string");

        $constructorComment = "@param \$value\n";
        $constructorComment.= "@throws InvalidValueException";

        $constants = array_map(function ($constant) {
            return 'self::' . ucfirst($constant);
        }, $values);
        $constructorBody = ' $possibleValues = [' . implode(', ', $constants) . '];
            if (!in_array($value, $possibleValues)) {
                throw new InvalidValueException($value . \' is not an allowed value for EnumPropertyFoo\');
            }
            $this->value = $value;';

        $constructor = $class->addMethod('__construct')
                            ->addComment($constructorComment)
                            ->addBody($constructorBody);
        $constructor->addParameter('value');
        $class->addMethod('getValue')
            ->addBody(' return $this->value;');
        return $namespace;
    }

    /**
     * @param string $className
     * @return PhpNamespace
     */
    private function createException($className) {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $namespace->addClass($className)
            ->addExtend('\Exception');
        return $namespace;
    }
}
