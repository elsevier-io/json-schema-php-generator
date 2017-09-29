<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class FooBar implements \JsonSerializable
{
    /** @var int */
    private $foo;

    /**
     * @param int $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    public function jsonSerialize() {
        return [
            'foo' => $this->foo,
        ];
    }
}
