<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SingleIntegerProperty implements \JsonSerializable
{
    /** @var integer */
    private $foo;

    /**
     * @param integer $foo
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