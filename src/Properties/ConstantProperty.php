<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\Method;

class ConstantProperty extends TypedProperty
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, 'string');
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = '{$this->value}';");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorComment(Method $constructor)
    {
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        return $constructor;
    }
}
