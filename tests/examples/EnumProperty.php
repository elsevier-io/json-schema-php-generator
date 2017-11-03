<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class EnumProperty implements \JsonSerializable
{
    /** @var EnumPropertyFoo */
    private $foo;
    /** @var EnumPropertyBar */
    private $bar;

    /**
     * @param EnumPropertyFoo $foo
     */
    public function __construct(EnumPropertyFoo $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @param EnumPropertyBar $value
     */
    public function setBar(EnumPropertyBar $value)
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
