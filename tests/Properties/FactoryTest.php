<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\Properties;

use Elsevier\JSONSchemaPHPGenerator\Properties\Factory;
use Elsevier\JSONSchemaPHPGenerator\Properties\IntegerProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\StringProperty;
use Elsevier\JSONSchemaPHPGenerator\Properties\UntypedProperty;

class FactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testWithNoTypeReturnsUntypedProperty() {
        $attributes = json_decode('{}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes);

        assertThat($property, is(anInstanceOf(UntypedProperty::class)));
    }

    public function testNumberReturnsIntegerProperty() {
        $attributes = json_decode('{ "type": "number"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes);

        assertThat($property, is(anInstanceOf(IntegerProperty::class)));
    }

    public function testStringReturnsStringProperty() {
        $attributes = json_decode('{ "type": "string"}');

        $factory = new Factory();
        $property = $factory->create('FooBar', $attributes);

        assertThat($property, is(anInstanceOf(StringProperty::class)));
    }
}