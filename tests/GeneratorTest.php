<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\Generator;
use Elsevier\JSONSchemaPHPGenerator\InvalidSchemaException;
use Hamcrest\MatcherAssert as h;
use Hamcrest\Matchers as m;

class GeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatesOutputDirIfNonExistent() {
        $outputDir = '/tmp/outputDir';
        $generator = new Generator($outputDir);

        h::assertThat(dir($outputDir), m::anInstanceOf(\Directory::class));
    }

    public function testEmptySchemaCreatesNoFiles() {
        $outputDir = '/tmp/outputDir';
        $generator = new Generator($outputDir);

        $generator->generate('{}');

        $actualOutputDir = new \FilesystemIterator($outputDir);
        h::assertThat($actualOutputDir, m::emptyTraversable());
    }

    public function testBasicSchemaCreatesOneClassFile() {
        $schema = '{"definitions": {
            "FooBar": {
                "properties": {
                    "foo": {"type": "integer"},
                    "bar": {"type": "string"}
                }
            }
        }}';
        $outputDir = '/tmp/outputDir';
        $generator = new Generator($outputDir);

        $generator->generate($schema);

        h::assertThat(is_file($outputDir . DIRECTORY_SEPARATOR . 'FooBar.php'), m::is(true));
    }

    public function testInvalidSchemaThrowsException() {
        $schema = '{';
        $outputDir = '/tmp/outputDir';
        $generator = new Generator($outputDir);

        $this->setExpectedException(InvalidSchemaException::class);
        $generator->generate($schema);
    }

    /**
     * @after
     */
    public function cleanOutOutputDir() {
        $outputDir = '/tmp/outputDir';
        $generatedFile = $outputDir . DIRECTORY_SEPARATOR . 'FooBar.php';
        if (is_file($generatedFile)) {
            unlink($generatedFile);
        }
        rmdir($outputDir);
    }
}
