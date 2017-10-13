<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ArrayProperty extends TypedProperty
{
    /**
     * @var string
     */
    private $arrayItemType;
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
        $this->arrayItemType = $type;
        parent::__construct($name, $type . '[]');
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        $constructor->addParameter($this->name)
            ->setTypeHint('array');
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function constructorBody()
    {
        return '$this->' . $this->name . ' = $this->filterFor' . $this->arrayItemType . '($' . $this->name . ');' . "\n";
    }

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment('@param ' . $this->type . ' $value')
            ->addBody('$this->' . $this->name . ' = $this->filterFor' . $this->arrayItemType . '($value);')
            ->addParameter('value')
            ->setTypeHint('array');
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addExtraMethodsTo(ClassType $class)
    {
        $class->addMethod('filterFor' . $this->arrayItemType)
            ->setVisibility('private')
            ->addComment("@param array \$array\n@return $this->type")
            ->addBody(
                'return array_filter($array, function ($item) {' . "\n" .
                '   return $item instanceof ' . $this->arrayItemType . ';' . "\n" .
                '});'
            )
            ->addParameter('array')
            ->setTypeHint('array');
        return $class;
    }
}
