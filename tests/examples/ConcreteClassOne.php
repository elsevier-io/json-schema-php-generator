<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ConcreteClassOne implements \JsonSerializable, IBar
{
    /** @var string */
    private $foo;

    /**
     * @param string $foo
     */
    public function __construct($foo)
    {
        $this->foo = (string)$foo;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
        ];
    }
}
