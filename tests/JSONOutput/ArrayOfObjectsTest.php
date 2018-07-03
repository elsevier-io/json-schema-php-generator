<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\ArrayOfObjects;
use Elsevier\JSONSchemaPHPGenerator\Examples\SubReference;
use PHPUnit\Framework\TestCase;

class ArrayOfObjectsTest extends TestCase
{
    public function testOutputsSimpleRequiredArray()
    {
        $subReferences = [
            new SubReference("foo", true),
            new SubReference("bar", false),
        ];
        $object = new ArrayOfObjects($subReferences);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                {
                    "foobar": "foo",
                    "baz": true
                },
                {
                    "foobar": "bar",
                    "baz": false
                }
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testOutputsRequiredAndOptionalArray()
    {
        $requiredSubReferences = [
            new SubReference("bar", false),
        ];
        $optionalSubReferences = [
            new SubReference("foo", true),
        ];
        $object = new ArrayOfObjects($requiredSubReferences);
        $object->setFoo($optionalSubReferences);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                {
                    "foobar": "bar",
                    "baz": false
                }
            ],
            "foo": [
                {
                    "foobar": "foo",
                    "baz": true
                }
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testFiltersArrayForInvalidValues()
    {
        $subReferences = [
            "string",
            new SubReference("foo", true),
            true
        ];
        $object = new ArrayOfObjects($subReferences);
        $json = json_encode($object);

        $expected = '{
            "bar": [
                {
                    "foobar": "foo",
                    "baz": true
                }
            ]
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
