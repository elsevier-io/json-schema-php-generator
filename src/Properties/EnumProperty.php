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
    public function addParameterTo(Method $constructor)
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
            ->addComment("@var {$this->enumName}");
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = \${$this->name};");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorComment(Method $constructor)
    {
        $constructor->addComment("@param $this->enumName \$$this->name");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function serializingCode()
    {
        return "    '{$this->name}' => \$this->{$this->name}," . PHP_EOL;
    }

    /**
     * @inheritdoc
     */
    public function optionalSerializingCode()
    {
        return <<<CODE
if (\$this->$this->name) {
   \$values['$this->name'] = \$this->$this->name;
}
CODE;
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

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment("@param $this->enumName \$value")
            ->addBody("\$this->$this->name = \$value;")
            ->addParameter('value')
            ->setTypeHint($this->defaultNamespace . '\\' . $this->enumName);
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addExtraMethodsTo(ClassType $class)
    {
        return $class;
    }
}
