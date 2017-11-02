<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\ObjectWithSubReferenceEnum;
use Elsevier\JSONSchemaPHPGenerator\Examples\SubReferenceEnumFoo;
use PHPUnit\Framework\TestCase;

class ObjectWithSubReferenceEnumTest extends TestCase
{

    public function testOutputsValueFromEnum()
    {
        $subReference = new SubReferenceEnumFoo(SubReferenceEnumFoo::FOO);
        $object = new ObjectWithSubReferenceEnum($subReference);
        $json = json_encode($object);

        $expected = $this->removeWhiteSpace('{
            "foo": "Foo"
        }');
        $this->assertEquals($expected, $json);
    }

    private function removeWhiteSpace($code)
    {
        return preg_replace('/\s+/', '', $code);
    }
}
