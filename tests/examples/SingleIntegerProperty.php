<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SingleIntegerProperty implements \JsonSerializable
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
