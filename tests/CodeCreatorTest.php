<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;

class CodeCreatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesClassWithIntegerProperty()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "number"}
            },
            "required": [
                "foo"
            ]
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
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = new CodeCreator('StringProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('StringProperty'));
    }
    
    public function testCreatesClassWithRequiredAndOptionalBooleanProperties()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "boolean"},
                "bar": {"type": "boolean"}
            },
            "required": [
                "foo"
            ]
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
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = new CodeCreator('EnumPropertyWithSingleValue', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('EnumPropertyWithSingleValue'));
    }

    public function testWithRequiredAndOptionalEnumPropertiesCreatesTargetClass()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                },
                "bar": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                }
            },
            "required": [
                "foo"
            ]
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
            },
            "required": [
                "foo"
            ]
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
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = new CodeCreator('EnumProperty', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasClassThatMatchesTheExample('InvalidValueException'));
    }

    public function testCreatesClassWithMultipleProperties()
    {
        $schema = json_decode('{
            "properties": {
                "bool": {"type": "boolean"},
                "enum": {
                    "enum": [
                        "Bar",
                        "Foo"
                    ],
                    "type": "string"
                },
                "string": {"type": "string"}
            },
            "required": [
                "bool",
                "enum",
                "string"
            ]
        }');
        $codeCreator = new CodeCreator('MultipleProperties', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('MultipleProperties'));
    }

    public function testCreatesTwoClassesForClassWithRequiredAndOptionalSubRefsDefined()
    {
        $schema = json_decode('{
            "definitions": {
                "SubReference": {
                    "properties": {
                        "foobar": {"type": "string"},
                        "baz": {"type": "boolean"}
                    },
                    "required": [
                        "foobar",
                        "baz"
                    ],
                    "type": "object"
                }
            },
            "properties": {
                "foo": {"type": "boolean"},
                "bar": {"$ref": "#/definitions/SubReference"},
                "baz": {"$ref": "#/definitions/SubReference"}
            },
            "type": "object",
            "required": [
                "foo",
                "bar"
            ]
        }');

        $codeCreator = new CodeCreator('ClassWithTwoSubRefs', 'Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(2)));
        assertThat($code, hasClassThatMatchesTheExample('ClassWithTwoSubRefs'));
        assertThat($code, hasClassThatMatchesTheExample('SubReference'));
    }
}
