<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ArrayOfObjects implements \JsonSerializable
{
    /** @var SubReference[] */
    private $bar;
    /** @var SubReference[] */
    private $foo;

    /**
     * @param SubReference[] $bar
     */
    public function __construct(array $bar)
    {
        $this->bar = $this->filterForSubReference($bar);
    }

    /**
     * @param array $array
     * @return SubReference[]
     */
    private function filterForSubReference(array $array)
    {
        return array_filter($array, function ($item) {
            return $item instanceof SubReference;
        });
    }

    /**
     * @param SubReference[] $value
     */
    public function setFoo(array $value)
    {
        $this->foo = $this->filterForSubReference($value);
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
