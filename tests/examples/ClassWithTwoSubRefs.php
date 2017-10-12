<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ClassWithTwoSubRefs implements \JsonSerializable
{
    /** @var SubReference */
    private $bar;

    /** @var SubReference */
    private $baz;

    /**
     * @param SubReference $bar
     */
    public function __construct(SubReference $bar)
    {
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
            'bar' => $this->bar,
        ];
        if ($this->baz) {
            $values['baz'] = $this->baz;
        }
        return $values;
    }
}

