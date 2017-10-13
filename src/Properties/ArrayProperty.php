<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ArrayProperty extends ObjectProperty
{
    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return '@param ' . $this->type . '[] $' . $this->name;
    }

    /**
     * @inheritdoc
     */
    public function addTo(ClassType $class)
    {
        $class->addProperty($this->name)
            ->setVisibility('private')
            ->addComment("@var " . $this->type  ."[]");
        return $class;
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
        return '$this->' . $this->name . ' = $this->filterFor' . $this->type . '($' . $this->name . ');' . "\n";
    }

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment('@param ' . $this->type . '[] $value')
            ->addBody('$this->' . $this->name . ' = $this->filterFor' . $this->type . '($value);')
            ->addParameter('value')
            ->setTypeHint('array');
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addMethodsTo(ClassType $class)
    {
        $class->addMethod('filterFor' . $this->type)
            ->setVisibility('private')
            ->addComment("@param array \$array\n@return $this->type[]")
            ->addBody(
                'return array_filter($array, function ($item) {' . "\n" .
                '   return $item instanceof ' . $this->type . ';' . "\n" .
                '});'
            )
            ->addParameter('array')
            ->setTypeHint('array');
        return $class;
    }
}
