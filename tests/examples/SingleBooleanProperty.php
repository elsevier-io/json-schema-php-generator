<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SingleBooleanProperty implements \JsonSerializable
{
    /** @var boolean */
    private $foo;

    /**
     * @param boolean $foo
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
