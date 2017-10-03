<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class EnumPropertyFoo {
    const Foo = 'Foo';
    const Bar = 'Bar';

    /**
     * @var string
     */
    private $value;

    /**
     * @param $value
     * @throws InvalidValueException
     */
    public function __construct($value)
    {
        $possibleValues = [self::Bar, self::Foo];
        if (!in_array($value, $possibleValues)) {
            throw new InvalidValueException($value . ' is not an allowed value for EnumPropertyFoo');
        }
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
