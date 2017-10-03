<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class CodeCreatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesClassWithIntegerProperty()
    {
        $schema = json_decode('{"definitions": {
            "IntegerProperty": {
                "properties": {
                    "foo": {"type": "number"}
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasKey('IntegerProperty'));
        $expected = $this->getExample('IntegerProperty.php');
        assertThat($this->removeWhiteSpace($code['IntegerProperty']), is(equalTo($expected)));
    }
    
    public function testCreatesClassWithStringProperty()
    {
        $schema = json_decode('{"definitions": {
            "StringProperty": {
                "properties": {
                    "foo": {"type": "string"}
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasKey('StringProperty'));
        $expected = $this->getExample('StringProperty.php');
        assertThat($this->removeWhiteSpace($code['StringProperty']), is(equalTo($expected)));
    }
    
    public function testCreatesClassWithBooleanProperty()
    {
        $schema = json_decode('{"definitions": {
            "BooleanProperty": {
                "properties": {
                    "foo": {"type": "boolean"}
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasKey('BooleanProperty'));
        $expected = $this->getExample('BooleanProperty.php');
        assertThat($this->removeWhiteSpace($code['BooleanProperty']), is(equalTo($expected)));
    }

    public function testCreatesClassWithEnumPropertyWithSingleValueAsConstant()
    {
        $schema = json_decode('{"definitions": {
            "EnumPropertyWithSingleValue": {
                "properties": {
                    "foo": {
                        "enum": [
                            "Bar"
                        ],
                        "type": "string"
                    }
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasKey('EnumPropertyWithSingleValue'));
        $expected = $this->getExample('EnumPropertyWithSingleValue.php');
        assertThat($this->removeWhiteSpace($code['EnumPropertyWithSingleValue']), is(equalTo($expected)));
    }

    public function testWithEnumPropertyCreatesTargetClass()
    {
        $schema = json_decode('{"definitions": {
            "EnumProperty": {
                "properties": {
                    "foo": {
                        "enum": [
                            "Foo",
                            "Bar"
                        ],
                        "type": "string"
                    }
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasKey('EnumProperty'));
        $expected = $this->getExample('EnumProperty.php');
        assertThat($this->removeWhiteSpace($code['EnumProperty']), is(equalTo($expected)));
//        assertThat($code, hasKey('EnumPropertyFoo'));
//        $expected = $this->getExample('EnumPropertyFoo.php');
//        assertThat($this->removeWhiteSpace($code['EnumPropertyFoo']), is(equalTo($expected)));
//        assertThat($code, hasKey('InvalidValueException'));
//        $expected = $this->getExample('InvalidValueException.php');
//        assertThat($this->removeWhiteSpace($code['InvalidValueException']), is(equalTo($expected)));
    }

    public function testWithEnumPropertyCreatesEnumClass()
    {
        $schema = json_decode('{"definitions": {
            "EnumProperty": {
                "properties": {
                    "foo": {
                        "enum": [
                            "Foo",
                            "Bar"
                        ],
                        "type": "string"
                    }
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(3));
        assertThat($code, hasKey('EnumPropertyFoo'));
        $expected = $this->getExample('EnumPropertyFoo.php');
        assertThat($this->removeWhiteSpace($code['EnumPropertyFoo']), is(equalTo($expected)));
    }

    /**
     * @param string $exampleName
     * @return string
     */
    public function getExample($exampleName) {
        $localFiles = new Local(__DIR__ . '/examples/');
        $examples = new Filesystem($localFiles);
        $example = $this->removeWhiteSpace($examples->read($exampleName));
        return substr($example, 5);
    }

    private function removeWhiteSpace($code) {
        return preg_replace('/\s+/', '', $code);
    }
}
