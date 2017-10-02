<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SingleStringProperty implements \JsonSerializable
{
    /** @var string */
    private $foo;

    /**
     * @param string $foo
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