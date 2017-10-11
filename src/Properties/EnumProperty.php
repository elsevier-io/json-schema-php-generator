<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class EnumProperty implements Property
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $defaultNamespace;
    /**
     * @var string
     */
    private $enumName;
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $values;

    /**
     * @param string $name
     * @param array $values
     * @param string $className
     * @param string $defaultNamespace
     */
    public function __construct($name, $values, $className, $defaultNamespace)
    {
        $this->className = $className;
        $this->defaultNamespace = $defaultNamespace;
        $this->name = $name;
        $this->values = $values;
        $this->enumName = $this->className . ucfirst($this->name);
    }

    /**
     * @inheritdoc
     */
    public function addConstructorParameter(Method $constructor)
    {
        $constructor->addParameter($this->name)
            ->setTypeHint($this->defaultNamespace . '\\' . $this->enumName);
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
    public function constructorBody()
    {
        return '$this->' . $this->name . ' = $' . $this->name . '->getValue();' . "\n";
    }

    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return "@param $this->enumName \$$this->name";
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
        $classes[$this->enumName] = $code->createEnum($this->enumName, $this->values);
        $classes['InvalidValueException'] = $code->createException('InvalidValueException');
        return $classes;
    }
}
