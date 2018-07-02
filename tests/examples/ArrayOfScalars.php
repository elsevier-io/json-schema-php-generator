<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ArrayOfScalars implements \JsonSerializable
{
    /** @var string[] */
    private $bar;
    /** @var float[] */
    private $foo;

    /**
     * @param string[] $bar
     */
    public function __construct(array $bar)
    {
        $this->bar = $this->filterForString($bar);
    }

    /**
     * @param array $array
     * @return string[]
     */
    private function filterForString(array $array)
    {
        return array_filter($array, function ($item) {
            return is_string($item);
        });
    }

    /**
     * @param float[] $value
     */
    public function setFoo(array $value)
    {
        $this->foo = $this->filterForFloat($value);
    }

    /**
     * @param array $array
     * @return float[]
     */
    private function filterForFloat(array $array)
    {
        return array_filter($array, function ($item) {
            return is_float($item);
        });
    }

    public function jsonSerialize()
    {
        $values = [
            'bar' => $this->bar,
        ];
        if ($this->foo) {
            $values['foo'] = $this->foo;
        }
        return $values;
    }
}
