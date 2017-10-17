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
    public function addParameterTo(Method $constructor);

    /**
     * @param ClassType $class
     * @return ClassType
     */
    public function addTo(ClassType $class);

    /**
     * @param Method $constructor
     * @return Method
     */
    public function addConstructorBody(Method $constructor);

    /**
     * @param Method $constructor
     * @return Method
     */
    public function addConstructorComment(Method $constructor);

    /**
     * @return string
     */
    public function serializingCode();

    /**
     * @return string
     */
    public function optionalSerializingCode();

    /**
     * @param CodeCreator $code
     * @return array
     */
    public function extraClasses(CodeCreator $code);

    /**
     * @param ClassType $class
     * @return ClassType
     */
    public function addSetterTo(ClassType $class);

    /**
     * @param ClassType $class
     * @return ClassType
     */
    public function addExtraMethodsTo(ClassType $class);
}
