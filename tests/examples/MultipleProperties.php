<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class MultipleProperties implements \JsonSerializable
{
    /** @var boolean */
    private $bool;

    /** @var MultiplePropertiesEnum */
    private $enum;

    /** @var string */
    private $string;

    /**
     * @param boolean $bool
     * @param MultiplePropertiesEnum $enum
     * @param string $string
     */
    public function __construct($bool, MultiplePropertiesEnum $enum, $string)
    {
        $this->bool = (bool)$bool;
        $this->enum = $enum;
        $this->string = (string)$string;
    }

    public function jsonSerialize()
    {
        return [
            'bool' => $this->bool,
            'enum' => $this->enum,
            'string' => $this->string,
        ];
    }
}
