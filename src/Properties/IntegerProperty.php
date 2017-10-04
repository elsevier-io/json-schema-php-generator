<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class IntegerProperty extends ScalarProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'integer');
    }
}