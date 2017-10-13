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
    public function constructorBody()
    {
        return "\$this->{$this->name} = '{$this->value}';" . PHP_EOL;
    }

    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        return $constructor;
    }
}
