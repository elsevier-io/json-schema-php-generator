<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

class BooleanProperty extends TypedProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'boolean');
    }
}
