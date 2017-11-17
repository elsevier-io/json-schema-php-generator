<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\StringProperty;
use PHPUnit\Framework\TestCase;

class StringPropertyTest extends TestCase
{
    public function testReturnsBasicString()
    {
        $object = new StringProperty('bar');
        $json = json_encode($object);

        $expected = '{
            "foo": "bar"
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testCastsNullToEmptyString()
    {
        $object = new StringProperty(null);
        $json = json_encode($object);

        $expected = '{
            "foo": ""
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
