<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\Method;

class StringProperty extends TypedProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'string');
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = (string)\${$this->name};");
        return $constructor;
    }
}
