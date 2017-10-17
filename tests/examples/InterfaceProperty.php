<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class InterfaceProperty implements \JsonSerializable
{
    /** @var IBar */
    private $bar;

    /**
     * @param IBar $bar
     */
    public function __construct(IBar $bar)
    {
        $this->bar = $bar;
    }

    public function jsonSerialize()
    {
        return [
            'bar' => $this->bar,
        ];
    }
}
