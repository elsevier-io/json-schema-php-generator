<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class Factory
{
    /**
     * @param string $name
     * @param stdObject $attributes
     * @return Property
     */
    public function create($name, $attributes) {
        if (!isset($attributes->type)) {
            return new UntypedProperty();
        }
        if (isset($attributes->enum)) {
            if (count($attributes->enum) === 1) {
                return new ConstantProperty($name, $attributes->enum[0]);
            } else {
                return;
            }
        }
        if ($attributes->type === 'number') {
            return new IntegerProperty($name);
        } elseif ($attributes->type === 'string' && !isset($attributes->enum)) {
            return new StringProperty($name);
        } elseif ($attributes->type === 'boolean') {
            return new BooleanProperty($name);
        }
    }
}