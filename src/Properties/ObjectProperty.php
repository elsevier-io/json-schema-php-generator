<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ObjectProperty extends TypedProperty
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param string $name
     * @param string $type
     * @param string $namespace
     */
    public function __construct($name, $type, $namespace)
    {
        $this->namespace = $namespace;
        parent::__construct($name, $type);
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        $constructor->addParameter($this->name)
            ->setTypeHint($this->namespace . '\\' . $this->type);
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment('@param ' . $this->type . ' $value')
            ->addBody("\$this->$this->name = \$value;")
            ->addParameter('value')
            ->setTypeHint($this->namespace . '\\' . $this->type);
        return $class;
    }
}
