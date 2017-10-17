<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;

class InterfaceProperty extends ObjectProperty
{
    /**
     * @var string[]
     */
    private $concreteClasses;

    /**
     * @param string $name
     * @param string $type
     * @param string $namespace
     * @param array $concreteClasses
     */
    public function __construct($name, $type, $namespace, $concreteClasses)
    {
        parent::__construct($name, $type, $namespace);
        $this->concreteClasses = $concreteClasses;
    }

    /**
     * @inheritdoc
     */
    public function extraClasses(CodeCreator $code)
    {
        return [ $this->type => $this->concreteClasses ];
    }
}

