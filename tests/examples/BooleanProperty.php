<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class BooleanProperty implements \JsonSerializable
{
    /** @var boolean */
    private $foo;
    /** @var boolean */
    private $bar;

    /**
     * @param boolean $foo
     */
    public function __construct($foo)
    {
        $this->foo = (bool)$foo;
    }

    /**
     * @param boolean $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    public function jsonSerialize()
    {
        $values = [
            'foo' => $this->foo,
        ];
        if ($this->bar) {
            $values['bar'] = $this->bar;
        }
        return $values;
    }
}
