<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests;

use Elsevier\JSONSchemaPHPGenerator\Generator;
use Hamcrest\MatcherAssert as h;
use Hamcrest\Matchers as m;

class GeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptySchema() {
        $generator = new Generator();

        $generator->generate('{}');

        $outputDir = new \FilesystemIterator(__DIR__ . DIRECTORY_SEPARATOR . 'outputDir/');
        h::assertThat($outputDir, m::emptyTraversable());
    }
}
