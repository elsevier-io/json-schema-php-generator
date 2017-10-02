<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\CodeCreator;
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
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');
        $generator = new Generator($fileSystem, $codeCreator);

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
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');
        $generator = new Generator($fileSystem, $codeCreator);

        $generator->generate($schema);

        h::assertThat($fileSystem->has('FooBar.php'), m::is(true));
    }

    public function testInvalidJsonThrowsException() {
        $schema = '{';
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');
        $generator = new Generator($this->createFilesystem(), $codeCreator);

        $this->setExpectedException(InvalidJsonException::class);
        $generator->generate($schema);
    }

    public function testInvalidJsonSchemaThrowsException() {
        $schema = '{"definitions": {
            "Baz": "invalid"
        }}';
        $codeCreator = new CodeCreator('Elsevier\JSONSchemaPHPGenerator\Examples');
        $generator = new Generator($this->createFilesystem(), $codeCreator);

        $this->setExpectedException(InvalidSchemaException::class);
        $generator->generate($schema);
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
