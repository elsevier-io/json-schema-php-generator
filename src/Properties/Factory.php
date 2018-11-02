<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Psr\Log\LoggerInterface;

class Factory
{
    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * @param string $name
     * @param stdObject $attributes
     * @param string $className
     * @param string $namespace
     * @return Property
     */
    public function create($name, $attributes, $className, $namespace)
    {
        if (isset($attributes->{'$ref'})) {
            $this->log->debug('Created Object property ' . $name);
            return new ObjectProperty($name, $this->extractTypeFromRef($attributes->{'$ref'}), $namespace);
        } elseif (isset($attributes->anyOf)) {
            $this->log->debug('Created Interface property ' . $name);
            $concreteClasses = array_map(function ($concreteClassRef) {
                return $this->extractTypeFromRef($concreteClassRef->{'$ref'});
            }, $attributes->anyOf);
            return new InterfaceProperty($name, 'I' . ucfirst($name), $namespace, $concreteClasses);
        } elseif (!isset($attributes->type)) {
            $this->log->debug('Created Untyped property ' . $name);
            return new UntypedProperty();
        }
        if (isset($attributes->enum)) {
            if (count($attributes->enum) === 1) {
                $this->log->debug('Created Constant property ' . $name);
                return new ConstantProperty($name, $attributes->enum[0]);
            } else {
                $this->log->debug('Created Enum property ' . $name);
                return new EnumProperty($name, $attributes->enum, $className, $namespace);
            }
        }
        if ($attributes->type === 'number') {
            $this->log->debug('Created Float property ' . $name);
            return new FloatProperty($name);
        } elseif ($attributes->type === 'string' && !isset($attributes->enum)) {
            $minLength = isset($attributes->minLength) ? $attributes->minLength : false;
            $maxLength = isset($attributes->maxLength) ? $attributes->maxLength : false;
            $this->log->debug('Created String property ' . $name);
            return new StringProperty($name, $minLength, $maxLength);
        } elseif ($attributes->type === 'boolean') {
            $this->log->debug('Created Boolean property ' . $name);
            return new BooleanProperty($name);
        } elseif ($attributes->type === 'array') {
            $this->log->debug('Created Array property ' . $name);
            $arrayItemType = isset($attributes->items->{'$ref'}) ? $this->extractTypeFromRef($attributes->items->{'$ref'}) : $this->extractTypeFromRef($attributes->items->{'type'});
            $arrayItemType = $arrayItemType === 'number' ? 'float' : $arrayItemType;
            return new ArrayProperty($name, $arrayItemType, $namespace);
        }
        return new UntypedProperty();
    }

    /**
     * @param string $ref
     * @return string
     */
    private function extractTypeFromRef($ref)
    {
        $typeParts = explode('/', $ref);
        return array_pop($typeParts);
    }
}
