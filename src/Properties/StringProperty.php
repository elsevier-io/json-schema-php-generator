<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class StringProperty implements Property
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function constructorBody() {
        return '$this->' . $this->name . ' = $' . $this->name . ';' . "\n";
    }

    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return '@param string $' . $this->name;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorParameter(Method $constructor) {
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
            ->addComment("@var string");
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function serializingCode(){
        return "    '" . $this->name . "'=>" . '$this->' . $this->name . ",\n";
    }
}