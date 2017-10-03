<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class CodeCreatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesClassWithIntegerProperty()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "number"}
            }
        }');
        $codeCreator = new CodeCreator('IntegerProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('IntegerProperty'));
    }
    
    public function testCreatesClassWithStringProperty()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "string"}
            }
        }');
        $codeCreator = new CodeCreator('StringProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('StringProperty'));
    }
    
    public function testCreatesClassWithBooleanProperty()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "boolean"}
            }
        }');
        $codeCreator = new CodeCreator('BooleanProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('BooleanProperty'));
    }

    public function testCreatesClassWithEnumPropertyWithSingleValueAsConstant()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {
                    "enum": [
                        "Bar"
                    ],
                    "type": "string"
                }
            }
        }');
        $codeCreator = new CodeCreator('EnumPropertyWithSingleValue', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('EnumPropertyWithSingleValue'));
    }

    public function testWithEnumPropertyCreatesTargetClass()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                }
            }
        }');
        $codeCreator = new CodeCreator('EnumProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasKey('EnumPropertyFoo'));
        assertThat($code, hasKey('InvalidValueException'));
        assertThat($code, hasClassThatMatchesTheExample('EnumProperty'));
    }

    public function testWithEnumPropertyCreatesEnumClass()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                }
            }
        }');
        $codeCreator = new CodeCreator('EnumProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasClassThatMatchesTheExample('EnumPropertyFoo'));
    }

    public function testWithEnumPropertyCreatesExceptionUsedByEnum()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                }
            }
        }');
        $codeCreator = new CodeCreator('EnumProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasClassThatMatchesTheExample('InvalidValueException'));
    }

}
