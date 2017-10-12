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
    public function create($schema)
    {
        if (!isset($schema->properties)) {
            return [];
        }
        $classes = [];
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($this->defaultClass)
                        ->addImplement('\JsonSerializable');
        $constructor = $class->addMethod('__construct');
        $constructorComment = [];
        $constructorBody = '';
        $serializableRequiredProperties = '';
        $serializableOptionalProperties = '';
        foreach ($schema->properties as $propertyName => $propertyAttributes) {
            $property = $this->properties->create($propertyName, $propertyAttributes, $this->defaultClass, $this->defaultNamespace);
            if ($this->isRequired($propertyName, $schema)) {
                $constructorBody.= $property->constructorBody();
                $constructorComment[] = $property->constructorComment();
                $constructor = $property->addParameterTo($constructor);
                $class = $property->addTo($class);
                $serializableRequiredProperties.= $property->serializingCode();
                $classes = array_merge($classes, $property->extraClasses($this));
            } else {
                $class = $property->addTo($class);
                $property->addSetterTo($class);
                $serializableOptionalProperties.= $property->optionalSerializingCode();
            }
        }
        $constructorComment = array_filter($constructorComment, function ($comment) {
            return !empty($comment);
        });
        if (!empty($constructorComment)) {
            $constructor->addComment(implode("\n", $constructorComment));
        }
        $constructor->addBody($constructorBody);
        if (!empty($serializableOptionalProperties)) {
            $serializableMethodBody = "\$values = [\n" . $serializableRequiredProperties . "];\n";
            $serializableMethodBody.= $serializableOptionalProperties;
            $serializableMethodBody.= "return \$values;\n";
        } else {
            $serializableMethodBody = "return [\n" . $serializableRequiredProperties . "];";
        }
        $class->addMethod('jsonSerialize')
            ->addBody($serializableMethodBody);
        $classes[$this->defaultClass] = $namespace;
        return $classes;
    }

    private function isRequired($property, $schema)
    {
        $requiredProperties = isset($schema->required) ? $schema->required : [];
        return in_array($property, $requiredProperties);
    }

    /**
     * @param string $className
     * @param array $values
     * @return PhpNamespace
     */
    public function createEnum($className, $values)
    {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($className);
        foreach ($values as $value) {
            $class->addConst(strtoupper($value), $value);
        }
        $class->addProperty('value')
            ->setVisibility('private')
            ->addComment("@var string");

        $constructorComment = "@param \$value\n";
        $constructorComment.= "@throws InvalidValueException";

        $constants = array_map(function ($constant) {
            return 'self::' . strtoupper($constant);
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
    public function createException($className)
    {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $namespace->addClass($className)
            ->addExtend('\Exception');
        return $namespace;
    }
}
