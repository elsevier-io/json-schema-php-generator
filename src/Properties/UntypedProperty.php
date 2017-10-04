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
    public function constructorBody()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function constructorComment()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function addConstructorParameter(Method $constructor)
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
    public function extraClasses(CodeCreator $code)
    {
        return [];
    }
}
