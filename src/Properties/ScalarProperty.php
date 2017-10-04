<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ScalarProperty implements Property
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function constructorBody()
    {
        return '$this->' . $this->name . ' = $' . $this->name . ';' . "\n";
    }

    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return '@param ' . $this->type . ' $' . $this->name;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorParameter(Method $constructor)
    {
        $constructor->addParameter($this->name);
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addTo(ClassType $class)
    {
        $class->addProperty($this->name)
            ->setVisibility('private')
            ->addComment("@var " . $this->type);
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function serializingCode()
    {
        return "    '" . $this->name . "'=>" . '$this->' . $this->name . ",\n";
    }

    /**
     * @inheritdoc
     */
    public function extraClasses(CodeCreator $code)
    {
        return [];
    }
}
