<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class Factory
{
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
            return new ObjectProperty($name, $this->extractTypeFromRef($attributes->{'$ref'}), $namespace);
        } elseif (isset($attributes->anyof)) {
            $concreteClasses = array_map(function ($concreteClassRef) {
                return $this->extractTypeFromRef($concreteClassRef->{'$ref'});
            }, $attributes->anyof);
            return new InterfaceProperty($name, 'I' . ucfirst($name), $namespace, $concreteClasses);
        } elseif (!isset($attributes->type)) {
            return new UntypedProperty();
        }
        if (isset($attributes->enum)) {
            if (count($attributes->enum) === 1) {
                return new ConstantProperty($name, $attributes->enum[0]);
            } else {
                return new EnumProperty($name, $attributes->enum, $className, $namespace);
            }
        }
        if ($attributes->type === 'number') {
            return new FloatProperty($name);
        } elseif ($attributes->type === 'string' && !isset($attributes->enum)) {
            return new StringProperty($name);
        } elseif ($attributes->type === 'boolean') {
            return new BooleanProperty($name);
        } elseif ($attributes->type === 'array') {
            return new ArrayProperty($name, $this->extractTypeFromRef($attributes->items->{'$ref'}), $namespace);
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
