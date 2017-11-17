<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\Method;

class BooleanProperty extends TypedProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'boolean');
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = (bool)\${$this->name};");
        return $constructor;
    }
}
