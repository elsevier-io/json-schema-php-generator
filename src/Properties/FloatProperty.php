<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class FloatProperty extends ScalarProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'float');
    }
}
