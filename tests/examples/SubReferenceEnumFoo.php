<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SubReferenceEnumFoo implements \JsonSerializable
{
    const FOO = 'Foo';
    const BAR = 'Bar';

    /** @var string */
    private $value;

    /**
     * @param $value
     * @throws InvalidValueException
     */
    public function __construct($value)
    {
        $possibleValues = [self::FOO, self::BAR];
        if (!in_array($value, $possibleValues)) {
            throw new InvalidValueException($value . ' is not an allowed value for SubReferenceEnumFoo');
        }
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
