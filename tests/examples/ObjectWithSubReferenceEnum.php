<?php

namespace Elsevier\JSONSchemaPHPGenerator\Examples;

class ObjectWithSubReferenceEnum implements \JsonSerializable
{
    /** @var SubReferenceEnumFoo */
    private $foo;

    /**
     * @param SubReferenceEnumFoo $foo
     */
    public function __construct(SubReferenceEnumFoo $foo)
    {
        $this->foo = $foo;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
        ];
    }
}
