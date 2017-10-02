<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\Generator;
use Elsevier\JSONSchemaPHPGenerator\InvalidJsonException;
use Elsevier\JSONSchemaPHPGenerator\InvalidSchemaException;
use Hamcrest\MatcherAssert as h;
use Hamcrest\Matchers as m;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class GeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptySchemaCreatesNoFiles() {
        $fileSystem = $this->createFilesystem();
        $generator = new Generator($fileSystem);

        $generator->generate('{}');

        h::assertThat($fileSystem->listContents(), m::is([]));
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
        $fileSystem = $this->createFilesystem();
        $generator = new Generator($fileSystem);

        $generator->generate($schema);

        h::assertThat($fileSystem->has('FooBar.php'), m::is(true));
    }

    public function testInvalidJsonThrowsException() {
        $schema = '{';
        $generator = new Generator($this->createFilesystem());

        $this->setExpectedException(InvalidJsonException::class);
        $generator->generate($schema);
    }

    public function testInvalidJsonSchemaThrowsException() {
        $schema = '{"definitions": {
            "Baz": "invalid"
        }}';
        $generator = new Generator($this->createFilesystem());

        $this->setExpectedException(InvalidSchemaException::class);
        $generator->generate($schema);
    }

    public function testCreatesClassWithSingleIntegerProperty() {
        $schema = '{"definitions": {
            "FooBar": {
                "properties": {
                    "foo": {"type": "integer"}
                }
            }
        }}';
        $fileSystem = $this->createFilesystem();
        $generator = new Generator($fileSystem);
        $examples = $this->createFilesystem(__DIR__ . '/examples/');

        $generator->generate($schema);

        $actual = preg_replace('/\s+/', '', $fileSystem->read('FooBar.php'));
        $expected = preg_replace('/\s+/', '', $examples->read('FooBar.php'));
        h::assertThat($actual, m::is(m::equalTo($expected)));
    }

    /**
     * @param string $rootDir
     * @return Filesystem
     */
    public function createFilesystem($rootDir = '/tmp/outputDir/') {
        $localFiles = new Local($rootDir);
        return new Filesystem($localFiles);
    }

    /**
     * @after
     */
    public function cleanOutOutputDir() {
        $fileSystem = $this->createFilesystem();
        $files = $fileSystem->listContents();
        foreach ($files as $file) {
            $fileSystem->delete($file['path']);
        }
    }
}
