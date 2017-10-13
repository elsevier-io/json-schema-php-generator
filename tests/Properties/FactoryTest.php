<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\Properties;

use Elsevier\JSONSchemaPHPGenerator\Properties\Factory;
use Elsevier\JSONSchemaPHPGenerator\Properties\BooleanProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\ConstantProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\EnumProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\FloatProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\ObjectProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\StringProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\UntypedProperty;

class FactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testWithNoTypeReturnsUntypedProperty()
    {
        $attributes = json_decode('{}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(UntypedProperty::class)));
    }

    public function testWithInvalidTypeReturnsUntypedProperty()
    {
        $attributes = json_decode('{ "type": "foobar" }');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(UntypedProperty::class)));
    }

    public function testNumberReturnsFloatProperty()
    {
        $attributes = json_decode('{ "type": "number"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(FloatProperty::class)));
    }

    public function testStringReturnsStringProperty()
    {
        $attributes = json_decode('{ "type": "string"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(StringProperty::class)));
    }

    public function testBooleanReturnsBooleanProperty()
    {
        $attributes = json_decode('{ "type": "boolean"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(BooleanProperty::class)));
    }

    public function testSingleValueEnumReturnsConstantProperty()
    {
        $attributes = json_decode(
            '{
                "enum": [
                    "Bar"
                ],
                "type": "string"
            }'
        );

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(ConstantProperty::class)));
    }

    public function testMultiValueEnumReturnsConstantProperty()
    {
        $attributes = json_decode(
            '{
                "enum": [
                    "Foo",
                    "Bar"
                ],
                "type": "string"
            }'
        );

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(EnumProperty::class)));
    }

    public function testObjectPropertyWithReferenceReturnsObjectProperty()
    {
        $attributes = json_decode('{"$ref": "#/definitions/SubReference"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes, 'Class', 'Example\\Namespace');

        assertThat($property, is(anInstanceOf(ObjectProperty::class)));
        assertThat($property->constructorComment(), is('@param SubReference $FooBar'));
    }
}
