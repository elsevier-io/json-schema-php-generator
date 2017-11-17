<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\Method;

class FloatProperty extends TypedProperty
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name, 'float');
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = (float)\${$this->name};");
        return $constructor;
    }
}
