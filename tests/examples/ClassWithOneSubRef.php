<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ClassWithOneSubRef implements \JsonSerializable
{
    /** @var boolean */
    private $foo;

    /** @var SubReference */
    private $bar;

    /**
     * @param boolean $foo
     * @param SubReference $bar
     */
    public function __construct($foo, SubReference $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }
}

