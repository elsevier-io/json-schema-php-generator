<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class TypedProperty implements Property
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $type;

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
        $constructor->addComment("@param {$this->type} \${$this->name}");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
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
        return [];
    }

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment('@param ' . $this->type . ' $value')
            ->addBody("\$this->$this->name = \$value;")
            ->addParameter('value');
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
