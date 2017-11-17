<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class FloatProperty implements \JsonSerializable
{
    /** @var float */
    private $foo;

    /**
     * @param float $foo
     */
    public function __construct($foo)
    {
        $this->foo = (float)$foo;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
        ];
    }
}
