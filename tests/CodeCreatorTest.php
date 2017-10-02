<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class CodeCreatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesClassWithSingleIntegerProperty()
    {
        $schema = json_decode('{"definitions": {
            "FooBar": {
                "properties": {
                    "foo": {"type": "integer"}
                }
            }
        }}');
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');

        $code = $codeCreator->create($schema);

        assertThat($code, arrayWithSize(1));
        assertThat($code, hasKey('FooBar'));
        $expected = $this->getExample('FooBar.php');
        assertThat($this->removeWhiteSpace($code['FooBar']), is(equalTo($expected)));
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
