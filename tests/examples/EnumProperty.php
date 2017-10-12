<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class EnumProperty implements \JsonSerializable
{
    /** @var string */
    private $foo;

    /**
     * @param EnumPropertyFoo $foo
     */
    public function __construct(EnumPropertyFoo $foo)
    {
        $this->foo = $foo->getValue();
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
        ];
    }
}
