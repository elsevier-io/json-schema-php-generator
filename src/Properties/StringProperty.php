<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class StringProperty extends TypedProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'string');
    }
}
