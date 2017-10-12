<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\Method;

class ObjectProperty extends ScalarProperty
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @inheritdoc
     */
    public function __construct($name, $type, $namespace)
    {
        $this->namespace = $namespace;
        $typeParts = explode('/', $type);
        parent::__construct($name, array_pop($typeParts));
    }

    /**
     * @inheritdoc
     */
    public function addParameterTo(Method $constructor)
    {
        $constructor->addParameter($this->name)
            ->setTypeHint($this->namespace . '\\' . $this->type);
        return $constructor;
    }
}
