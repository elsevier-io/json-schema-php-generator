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
        return "    '" . $this->name . "' => " . '$this->' . $this->name . ",\n";
    }

    /**
     * @inheritdoc
     */
    public function optionalSerializingCode()
    {
        return "if (\$this->$this->name) {\n" .
            "   \$values['$this->name'] = \$this->$this->name;\n" .
            "}\n";
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
    public function addMethodsTo(ClassType $class)
    {
        return $class;
    }
}
