<?php

namespace Elsevier\JSONSchemaPHPGenerator;

use Elsevier\JSONSchemaPHPGenerator\Properties\Factory;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $log;

    /**
     * @param string $defaultClass
     * @param string $defaultNamespace
     * @param LoggerInterface $logger
     */
    public function __construct($defaultClass, $defaultNamespace, LoggerInterface $logger)
    {
        $this->defaultClass = $defaultClass;
        $this->defaultNamespace = $defaultNamespace;
        $this->properties = new Factory($logger);
        $this->log = $logger;
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
        $classes = $this->createClass($schema, $this->defaultClass);
        $this->log->debug('Created default class ' . $this->defaultClass . ' (' . implode(', ', array_keys($classes)) . ')');
        $references = isset($schema->definitions) ? $schema->definitions : [];
        foreach ($references as $className => $classDefinition) {
            if (isset($classDefinition->type) && $classDefinition->type === 'object') {
                $classesToAdd = $this->createClass($classDefinition, $className);
                $this->log->debug('Created class ' . $className . ' (' . implode(', ', array_keys($classesToAdd)) . ')');
                $classes = array_merge($classes, $classesToAdd);
            } else {
                $classes[$className] = $this->createEnum($className, $classDefinition->enum);
                $this->log->debug('Created enum ' . $className);
            }
        }
        $classes = $this->handleInterfaces($classes);
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
        $class = $namespace->addClass($className)
            ->addImplement('\JsonSerializable');
        $constantNames = [];
        foreach ($values as $value) {
            $constantName = $this->normalizeEnumConstantName($value);
            $class->addConst($constantName, $value);
            $constantNames[] = $constantName;
        }
        $class->addProperty('value')
            ->setVisibility('private')
            ->addComment("@var string");

        $constructorComment = "@param \$value\n";
        $constructorComment.= "@throws InvalidValueException";

        $constants = array_map(function ($constant) {
            return 'self::' . $constant;
        }, $constantNames);
        $constantsList = implode(', ', $constants);
        $constructorBody = " \$possibleValues = [$constantsList];\n" .
            "if (!in_array(\$value, \$possibleValues)) {\n" .
            "   throw new InvalidValueException(\$value . ' is not an allowed value for $className');\n" .
            "}\n" .
            "\$this->value = \$value;";

        $constructor = $class->addMethod('__construct')
                            ->addComment($constructorComment)
                            ->addBody($constructorBody);
        $constructor->addParameter('value');
        $class->addMethod('jsonSerialize')
            ->addBody('return $this->value;');
        return $namespace;
    }

    /**
     * Naming rules for constants are a little looser than just alpha characters but
     * this will work as a rough approximation
     *
     * @param $value
     * @return string
     */
    private function normalizeEnumConstantName($value)
    {
        return strtoupper(preg_replace('/[^A-Za-z]/', '_', $value));
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

    /**
     * @param mixed $schema
     * @param string $className
     * @return array
     */
    private function createClass($schema, $className)
    {
        $classes = [];
        $namespace = new PhpNamespace($this->defaultNamespace);
        $class = $namespace->addClass($className)
            ->addImplement('\JsonSerializable');
        $constructor = $class->addMethod('__construct');
        $serializableRequiredProperties = '';
        $serializableOptionalProperties = '';
        $propertyOrder;
        if(isset($schema->propertyOrder)) {
            $propertyOrder = $schema->propertyOrder;
        } else {
            $propertyOrder = array_keys(get_object_vars($schema->properties));
        }
        foreach ($propertyOrder as $propertyName) {
            $propertyAttributes = $schema->properties->$propertyName;
            $property = $this->properties->create($propertyName, $propertyAttributes, $className, $this->defaultNamespace);
            if ($this->isRequired($propertyName, $schema)) {
                $constructor = $property->addConstructorBody($constructor);
                $constructor = $property->addConstructorComment($constructor);
                $constructor = $property->addParameterTo($constructor);
                $class = $property->addTo($class);
                $serializableRequiredProperties .= $property->serializingCode();
                $classes = array_merge($classes, $property->extraClasses($this));
            } else {
                $class = $property->addTo($class);
                $class = $property->addSetterTo($class);
                $serializableOptionalProperties .= $property->optionalSerializingCode();
            }
            $property->addExtraMethodsTo($class);
        }
        if (!empty($serializableOptionalProperties)) {
            $serializableMethodBody = "\$values = [\n" . $serializableRequiredProperties . "];\n";
            $serializableMethodBody .= $serializableOptionalProperties;
            $serializableMethodBody .= "return \$values;\n";
        } else {
            $serializableMethodBody = "return [\n" . $serializableRequiredProperties . "];";
        }
        $class->addMethod('jsonSerialize')
            ->addBody($serializableMethodBody);
        $classes[$className] = $namespace;
        return $classes;
    }

    /**
     * @param $classes
     * @return PhpNamespace[]
     */
    private function handleInterfaces($classes)
    {
        $interfaces = array_filter($classes, function ($class) {
            return is_array($class);
        });
        foreach ($interfaces as $interface => $concreteClasses) {
            $this->log->debug('Handling interface ' . $interface);
            $classes[$interface] = $this->createInterface($interface);
            foreach ($concreteClasses as $concreteClass) {
                if (!isset($classes[$concreteClass]) || !($classes[$concreteClass] instanceof PhpNamespace)) {
                    continue;
                }
                /* @var PhpNamespace $namespace */
                $namespace = $classes[$concreteClass];
                $namespace->getClasses()[$concreteClass]->addImplement($this->defaultNamespace . '\\' . $interface);
            }
        }
        return $classes;
    }

    /**
     * @param string $interface
     * @return PhpNamespace
     */
    private function createInterface($interface)
    {
        $namespace = new PhpNamespace($this->defaultNamespace);
        $namespace->addClass($interface)
            ->setType(ClassType::TYPE_INTERFACE);
        return $namespace;
    }
}
