<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class StringPropertyWithLengthValidation implements \JsonSerializable
{
    /** @var string */
    private $foo;

    /**
     * @param string $foo
     */
    public function __construct($foo)
    {
        $this->foo = $this->ensureMaximumLength($this->ensureMinimumLength((string)$foo));
    }

    private function ensureMinimumLength($value)
    {
        if (mb_strlen($value) < 1) {
            throw new InvalidValueException($value . ' is less than the minimum specified length of 1');
        }
        return $value;
    }

    private function ensureMaximumLength($value)
    {
        if (mb_strlen($value) > 15) {
            throw new InvalidValueException($value . ' is more than the maximum specified length of 15');
        }
        return $value;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
        ];
    }
}
