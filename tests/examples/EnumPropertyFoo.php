<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class EnumPropertyFoo implements \JsonSerializable
{
    const FOO = 'Foo';
    const BAR = 'Bar';
    const FOO_BAR = 'Foo Bar';

    /** @var string */
    private $value;

    /**
     * @param $value
     * @throws InvalidValueException
     */
    public function __construct($value)
    {
        $possibleValues = [self::FOO, self::BAR, self::FOO_BAR];
        if (!in_array($value, $possibleValues)) {
            throw new InvalidValueException($value . ' is not an allowed value for EnumPropertyFoo');
        }
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
