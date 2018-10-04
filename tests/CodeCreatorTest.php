<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CodeCreatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var LoggerInterface
     */
    private $log;

    public function setUp()
    {
        $this->log = new Logger('UnitTestLogger');
        $this->log->pushHandler(new NullHandler());
    }

    public function testCreatesClassWithNumberProperty()
    {
        $schema = json_decode('{
            "properties": {
                "foo": {"type": "number"}
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('FloatProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasClassThatMatchesTheExample('FloatProperty'));
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
        $codeCreator = $this->buildCodeCreator('StringProperty');

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
        $codeCreator = $this->buildCodeCreator('BooleanProperty');

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
        $codeCreator = $this->buildCodeCreator('EnumPropertyWithSingleValue');

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
                        "Bar",
                        "Foo Bar"
                    ],
                    "type": "string"
                },
                "bar": {
                    "enum": [
                        "Foo",
                        "Bar",
                        "Foo Bar"
                    ],
                    "type": "string"
                }
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('EnumProperty');

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
                        "Bar",
                        "Foo Bar"
                    ],
                    "type": "string"
                }
            },
            "required": [
                "foo"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('EnumProperty');

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
        $codeCreator = $this->buildCodeCreator('EnumProperty');

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
        $codeCreator = $this->buildCodeCreator('MultipleProperties');

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
                "bar": {"$ref": "#/definitions/SubReference"},
                "baz": {"$ref": "#/definitions/SubReference"}
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');

        $codeCreator = $this->buildCodeCreator('ClassWithTwoSubRefs');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(2)));
        assertThat($code, hasClassThatMatchesTheExample('ClassWithTwoSubRefs'));
        assertThat($code, hasClassThatMatchesTheExample('SubReference'));
    }

    public function testWithEnumPropertyInReferenceClassCreatesEnumClass()
    {
        $schema = json_decode('{
            "definitions": {
                "SubReferenceEnum": {
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
                    ],
                    "type": "object"
                }
            },
            "properties": {
                "bar": {"$ref": "#/definitions/SubReferenceEnum"}
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('EnumProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('SubReferenceEnumFoo'));
    }

    public function testWithNamedEnumProperty()
    {
        $schema = json_decode('{
            "definitions": {
                "SubReferenceEnumFoo": {
                    "enum": [
                        "Foo",
                        "Bar"
                    ],
                    "type": "string"
                }
            },
            "properties": {
                "foo": {"$ref": "#/definitions/SubReferenceEnumFoo"}
            },
            "type": "object",
            "required": [
                "foo"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('ObjectWithSubReferenceEnum');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(2)));
        assertThat($code, hasClassThatMatchesTheExample('ObjectWithSubReferenceEnum'));
        assertThat($code, hasClassThatMatchesTheExample('SubReferenceEnumFoo'));
    }

    public function testWithArrayOfReferencedObjects()
    {
        $schema = json_decode('{
            "properties": {
                "bar": {
                    "items": {
                        "$ref": "#/definitions/SubReference"
                    },
                    "type": "array"
                },
                "foo": {
                    "items": {
                        "$ref": "#/definitions/SubReference"
                    },
                    "type": "array"
                }
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('ArrayOfObjects');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('ArrayOfObjects'));
    }

    public function testWithArrayOfScalarTypes()
    {
        $schema = json_decode('{
            "properties": {
                "bar": {
                    "items": {
                        "type": "string"
                    },
                    "type": "array"
                },
                "foo": {
                    "items": {
                        "type": "number"
                    },
                    "type": "array"
                }
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('ArrayOfScalars');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('ArrayOfScalars'));
    }

    public function testWithInterfacePropertyCreatesReferencingClass()
    {
        $schema = json_decode('{
            "definitions": {
                "ConcreteClassOne": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "type": "object"
                },
                "ConcreteClassTwo": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "type": "object"
                }
            },
            "properties": {
                "bar": {
                    "anyOf": [
                        {"$ref": "#/definitions/ConcreteClassOne"},
                        {"$ref": "#/definitions/ConcreteClassTwo"}
                    ]
                }
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('InterfaceProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('InterfaceProperty'));
    }

    public function testWithInterfacePropertyCreatesInterface()
    {
        $schema = json_decode('{
            "definitions": {
                "ConcreteClassOne": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "type": "object"
                },
                "ConcreteClassTwo": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "type": "object"
                }
            },
            "properties": {
                "bar": {
                    "anyOf": [
                        {"$ref": "#/definitions/ConcreteClassOne"},
                        {"$ref": "#/definitions/ConcreteClassTwo"}
                    ]
                }
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('InterfaceProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('IBar'));
    }

    public function testWithInterfacePropertyConcreteClassExtendInterface()
    {
        $schema = json_decode('{
            "definitions": {
                "ConcreteClassOne": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "required": [ "foo" ],
                    "type": "object"
                },
                "ConcreteClassTwo": {
                    "properties": {
                        "foo": { "type": "string" }
                    },
                    "required": [ "foo" ],
                    "type": "object"
                }
            },
            "properties": {
                "bar": {
                    "anyOf": [
                        {"$ref": "#/definitions/ConcreteClassOne"},
                        {"$ref": "#/definitions/ConcreteClassTwo"}
                    ]
                }
            },
            "type": "object",
            "required": [
                "bar"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('InterfaceProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(2)));
        assertThat($code, hasClassThatMatchesTheExample('ConcreteClassOne'));
        assertThat($code, hasClassThatMatchesTheExample('ConcreteClassTwo'));
    }

    public function testCreateOrderedConstructors()
    {
        $schema = json_decode('{
            "properties": {
                "alpha": {"type": "string"},
                "bravo": {"type": "string"},
                "charlie": {"type": "string"},
                "delta": {"type": "string"}
            },
            "required": [
                "alpha",
                "bravo",
                "charlie"
            ],
            "propertyOrder": [
                "charlie",
                "bravo",
                "alpha",
                "delta"
            ]
        }');
        $codeCreator = $this->buildCodeCreator('MultipleOrderedProperties');
        $code = $codeCreator->create($schema);
        assertThat($code, hasClassThatMatchesTheExample('MultipleOrderedProperties'));
    }

    public function testUnrequieredTypeIsCreated()
    {
        $schema = json_decode('{
            "definitions": {
                "ICharlie": {
                    "additionalProperties": false,
                    "properties": {
                        "charlie": {
                            "type": "string"
                        }
                    },
                    "propertyOrder": [
                        "charlie"
                    ],
                    "required": [
                        "charlie"
                    ],
                    "type": "object"
                },
                "IBravo": {
                    "additionalProperties": false,
                    "properties": {
                        "bravo": {
                            "type": "string"
                        }
                    },
                    "propertyOrder": [
                        "bravo"
                    ],
                    "required": [
                        "bravo"
                    ],
                    "type": "object"
                }
            },
            "properties": {
                "bar": {
                    "anyOf": [
                        {
                            "$ref": "#/definitions/ICharlie"
                        },
                        {
                            "$ref": "#/definitions/IBravo"
                        }
                    ]
                }
            },
            "propertyOrder": [
                "bar"
            ]
        }');

        $codeCreator = $this->buildCodeCreator('InterfaceProperty');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(atLeast(1)));
        assertThat($code, hasClassThatMatchesTheExample('IBar'));
    }

    private function buildCodeCreator($defaultClass)
    {
        return new CodeCreator($defaultClass, 'Elsevier\JSONSchemaPHPGenerator\Examples', $this->log);
    }
}
