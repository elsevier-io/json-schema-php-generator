<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class EnumProperty implements \JsonSerializable
{
    /** @var string */
    private $foo;
    /** @var string */
    private $bar;

    /**
     * @param EnumPropertyFoo $foo
     */
    public function __construct(EnumPropertyFoo $foo)
    {
        $this->foo = $foo->getValue();
    }

    /**
     * @param EnumPropertyBar $value
     */
    public function setBar(EnumPropertyBar $value)
    {
        $this->bar = $value->getValue();
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
