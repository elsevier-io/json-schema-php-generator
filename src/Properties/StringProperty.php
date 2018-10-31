<?php

namespace Elsevier\JSONSchemaPHPGenerator\Properties;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class StringProperty extends TypedProperty
{
    /**
     * @var integer|false
     */
    private $minLength;
    /**
     * @var integer|false
     */
    private $maxLength;

    /**
     * @param string $name
     * @param integer|false $minLength
     * @param integer|false $maxLength
     */
    public function __construct($name, $minLength = false, $maxLength = false)
    {
        parent::__construct($name, 'string');
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    /**
     * @inheritdoc
     */
    public function addConstructorBody(Method $constructor)
    {
        $value = "(string)\${$this->name}";
        if ($this->minLength !== false) {
            $value = "\$this->ensureMinimumLength($value)";
        }
        if ($this->maxLength !== false) {
            $value = "\$this->ensureMaximumLength($value)";
        }
        $constructor->addBody("\$this->{$this->name} = {$value};");
        return $constructor;
    }

    /**
     * @inheritdoc
     */
    public function addExtraMethodsTo(ClassType $class)
    {
        if ($this->minLength !== false) {
            $body = <<<BODY
                if (mb_strlen(\$value) < {$this->minLength}) {
                    throw new InvalidValueException(\$value . ' is less than the minimum specified length of {$this->minLength}');
                }
                return \$value;
BODY;
            $class->addMethod('ensureMinimumLength')
                ->setVisibility('private')
                ->addBody($body)
                ->addParameter('value');
        }
        if ($this->maxLength !== false) {
            $body = <<<BODY
                if (mb_strlen(\$value) > {$this->maxLength}) {
                    throw new InvalidValueException(\$value . ' is more than the maximum specified length of {$this->maxLength}');
                }
                return \$value;
BODY;
            $class->addMethod('ensureMaximumLength')
                ->setVisibility('private')
                ->addBody($body)
                ->addParameter('value');
        }
        return $class;
    }
}
