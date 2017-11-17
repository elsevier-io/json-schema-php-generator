<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\BooleanProperty;
use PHPUnit\Framework\TestCase;

class BooleanPropertyTest extends TestCase
{
    public function testReturnsBooleanValue()
    {
        $object = new BooleanProperty(true);
        $json = json_encode($object);

        $expected = '{
            "foo": true
        }';
        assertThat($json, matchesJSONOutput($expected));
    }

    public function testCastsNullToFalse()
    {
        $object = new BooleanProperty(null);
        $json = json_encode($object);

        $expected = '{
            "foo": false
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
