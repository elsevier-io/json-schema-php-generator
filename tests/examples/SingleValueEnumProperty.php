<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SingleValueEnumProperty implements \JsonSerializable
{
    /** @var string */
    private $foo;

    /**
     *
     */
    public function __construct()
    {
        $this->foo = 'Bar';
    }

    public function jsonSerialize() {
        return [
            'foo' => $this->foo,
        ];
    }
}
