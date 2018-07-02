<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ArrayProperty extends TypedProperty
{
    /**
     * @var string
     */
    private $arrayItemType;
    /**
     * @var string
     */
    protected $namespace;
    /**
     * @var string
     */
    protected $filterMethodName;
    /**
     * @var string
     */
    protected $itemFilter;

    /**
     * @param string $name
     * @param string $type
     * @param string $namespace
     */
    public function __construct($name, $type, $namespace)
    {
        $this->namespace = $namespace;
        $this->arrayItemType = $type;
        parent::__construct($name, $type . '[]');
        $this->filterMethodName = 'filterFor' . ucfirst($this->arrayItemType);
        if (array_search($type, ['string', 'float', 'boolean']) !== false) {
            $this->itemFilter = "is_{$this->arrayItemType}(\$item)";
        } else {
            $this->itemFilter = "\$item instanceof {$this->arrayItemType}";
        }
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
    public function addConstructorBody(Method $constructor)
    {
        $constructor->addBody("\$this->{$this->name} = \$this->{$this->filterMethodName}(\${$this->name});");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addSetterTo(ClassType $class)
    {
        $class->addMethod('set' . ucfirst($this->name))
            ->addComment("@param {$this->type} \$value")
            ->addBody("\$this->{$this->name} = \$this->{$this->filterMethodName}(\$value);")
            ->addParameter('value')
            ->setTypeHint('array');
        return $class;
    }

    /**
     * @inheritdoc
     */
    public function addExtraMethodsTo(ClassType $class)
    {
        $class->addMethod($this->filterMethodName)
            ->setVisibility('private')
            ->addComment("@param array \$array\n@return $this->type")
            ->addBody(<<<CODE
return array_filter(\$array, function (\$item) {
   return {$this->itemFilter};
});
CODE
            )
            ->addParameter('array')
            ->setTypeHint('array');
        return $class;
    }
}
