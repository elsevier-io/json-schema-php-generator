<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\FloatProperty;
use PHPUnit\Framework\TestCase;

class FloatPropertyTest extends TestCase
{
    public function testReturnsFloatValue()
    {
        $object = new FloatProperty(2.5);
        $json = json_encode($object);

        $expected = '{
            "foo": 2.5
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testReturnsIntegerValue()
    {
        $object = new FloatProperty(2);
        $json = json_encode($object);

        $expected = '{
            "foo": 2
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testCastsNullToEmptyString()
    {
        $object = new FloatProperty(null);
        $json = json_encode($object);

        $expected = '{
            "foo": 0
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
