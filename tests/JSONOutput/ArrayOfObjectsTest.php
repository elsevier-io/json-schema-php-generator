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

        $expected = $this->removeWhiteSpace('{
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
        }');
        $this->assertEquals($expected, $json);
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

        $expected = $this->removeWhiteSpace('{
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
        }');
        $this->assertEquals($expected, $json);
    }

    public function testFiltersArrayForInvalidValues()
    {
        $subReferences = [
            new SubReference("foo", true),
            "string",
            true
        ];
        $object = new ArrayOfObjects($subReferences);
        $json = json_encode($object);

        $expected = $this->removeWhiteSpace('{
            "bar": [
                {
                    "foobar": "foo",
                    "baz": true
                }
            ]
        }');
        $this->assertEquals($expected, $json);
    }

    private function removeWhiteSpace($code)
    {
        return preg_replace('/\s+/', '', $code);
    }
}
