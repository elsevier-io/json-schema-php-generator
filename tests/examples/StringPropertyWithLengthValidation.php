<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class StringPropertyWithLengthValidation implements \JsonSerializable
{
    /** @var string */
    private $bar;
    /** @var string */
    private $baz;
    /** @var string */
    private $foo;

    /**
     * @param string $baz
     * @param string $foo
     * @throws InvalidValueException
     */
    public function __construct($baz, $foo)
    {
        $this->baz = $this->ensureMaximumLength($this->ensureMinimumLength((string)$baz));
        $this->foo = $this->ensureMaximumLength($this->ensureMinimumLength((string)$foo));
    }

    /**
     * @param string $bar
     * @throws InvalidValueException
     */
    public function setBar($bar)
    {
        $this->bar = $this->ensureMaximumLength($this->ensureMinimumLength((string)$bar));
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
        $values = [
            'baz' => $this->baz,
            'foo' => $this->foo,
        ];
        if ($this->bar) {
            $values['bar'] = $this->bar;
        }
        return $values;
    }
}
