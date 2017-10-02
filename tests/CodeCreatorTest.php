<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
use Hamcrest\MatcherAssert as h;
use Hamcrest\Matchers as m;
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

        h::assertThat($code, m::arrayWithSize(1));
        h::assertThat($code, m::hasKey('FooBar'));
        $expected = $this->getExample('FooBar.php');
        h::assertThat($this->removeWhiteSpace($code['FooBar']), m::is(m::equalTo($expected)));
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
