<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\ArrayOfScalars;
use PHPUnit\Framework\TestCase;

class ArrayOfScalarsTest extends TestCase
{
    public function testOutputsSimpleRequiredArray()
    {
        $strings = [
            "baz",
            "bar",
        ];
        $object = new ArrayOfScalars($strings);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                "baz",
                "bar"
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testOutputsRequiredAndOptionalArray()
    {
        $requiredStrings = [
            "baz",
        ];
        $optionalFloats = [
            1.23
        ];
        $object = new ArrayOfScalars($requiredStrings);
        $object->setFoo($optionalFloats);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                "baz"
            ],
            "foo": [
                1.23
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testFiltersArrayForInvalidValues()
    {
        $subReferences = [
            "string",
            new \StdClass("foo", true),
            true
        ];
        $object = new ArrayOfScalars($subReferences);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                "string"
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
