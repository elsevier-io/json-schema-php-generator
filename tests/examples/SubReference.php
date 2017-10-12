<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class SubReference implements \JsonSerializable
{
    /** @var string */
    private $foobar;

    /** @var boolean */
    private $baz;

    /**
     * @param string $foobar
     * @param boolean $baz
     */
    public function __construct($foobar, $baz)
    {
        $this->foobar = $foobar;
        $this->baz = $baz;
    }

    public function jsonSerialize()
    {
        return [
            'foobar' => $this->foobar,
            'baz' => $this->baz,
        ];
    }
}
