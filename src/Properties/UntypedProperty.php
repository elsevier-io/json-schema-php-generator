<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class UntypedProperty implements Property
{

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorComment(Method $constructor)
    {
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addTo(ClassType $class)
    {
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function serializingCode()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function optionalSerializingCode()
    {
        return '';
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
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addExtraMethodsTo(ClassType $class)
    {
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function getConstructorException(array $constructorExceptions)
    {
        return $constructorExceptions;
    }
}
