<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

interface Property
{
    /**
     * @param Method $constructor
     * @return Method
     */
    public function addConstructorParameter(Method $constructor);

    /**
     * @param ClassType $class
     * @return ClassType
     */
    public function addTo(ClassType $class);

    /**
     * @return string
     */
    public function constructorBody();

    /**
     * @return string
     */
    public function constructorComment();

    /**
     * @return string
     */
    public function serializingCode();

    /**
     * @param CodeCreator $code
     * @return array
     */
    public function extraClasses(CodeCreator $code);
}