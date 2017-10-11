<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class OneRequiredOneOptional implements \JsonSerializable
{
    /** @var boolean */
    private $foo;

    /** @var string */
    private $bar;

    /**
     * @param boolean $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @param string $value
     */
    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function jsonSerialize()
    {
        $values = [
            'foo' => $this->foo,
        ];
        if ($this->bar) {
            $values['bar'] = $this->bar;
        }
        return $values;
    }
}
