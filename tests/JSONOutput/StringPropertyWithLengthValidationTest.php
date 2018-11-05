<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\JSONOutput;

use Elsevier\JSONSchemaPHPGenerator\Examples\InvalidValueException;
use Elsevier\JSONSchemaPHPGenerator\Examples\StringPropertyWithLengthValidation;
use PHPUnit\Framework\TestCase;

class StringPropertyWithLengthValidationTest extends TestCase
{
    public function testRejectsStringLessThanMinLength()
    {
        try {
            $object = new StringPropertyWithLengthValidation('', 'string');
            assertThat(true, is(false));
        } catch (\Exception $e) {
            assertThat($e, is(anInstanceOf(InvalidValueException::class)));
        }
    }

    public function testRejectsStringMoreThanMaxLength()
    {
        try {
            $object = new StringPropertyWithLengthValidation(str_repeat('a', 16), 'string');
            assertThat(true, is(false));
        } catch (\Exception $e) {
            assertThat($e, is(anInstanceOf(InvalidValueException::class)));
        }
    }

    public function testAcceptsStringWithinLengthBounds()
    {
        $object = new StringPropertyWithLengthValidation('bar', 'string');
        $object->setBar('alpha');
        $json = json_encode($object);

        $expected = '{
            "baz": "bar",
            "foo": "string",
            "bar": "alpha"
        }';
        assertThat($json, matchesJSONOutput($expected));
    }
}
