<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ClassWithTwoSubRefs implements \JsonSerializable
{
    /** @var boolean */
    private $foo;

    /** @var SubReference */
    private $bar;

    /** @var SubReference */
    private $baz;

    /**
     * @param boolean $foo
     * @param SubReference $bar
     */
    public function __construct($foo, SubReference $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * @param SubReference $value
     */
    public function setBaz(SubReference $value)
    {
        $this->baz = $value;
    }


    public function jsonSerialize()
    {
        $values = [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
        if ($this->baz) {
            $values['baz'] = $this->baz;
        }
        return $values;
    }
}

