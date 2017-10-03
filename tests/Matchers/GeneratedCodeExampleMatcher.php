<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\Matchers;

use Hamcrest\BaseMatcher;
use Hamcrest\Description;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class GeneratedCodeExampleMatcher extends BaseMatcher
{
    private $className;
    private $expectedCode;

    public function __construct($className)
    {
        $this->className = $className;
        $this->expectedCode = $this->getExample($className . '.php');
    }

    public function matches($generatedCode)
    {
        assertThat($generatedCode, hasKey($this->className));
        $actualCode = $this->removeWhiteSpace($generatedCode[$this->className]);
        return $actualCode === $this->expectedCode;
    }

    public function describeTo(Description $description)
    {
        $description->appendText('Generated code to equal the expected sample code');
        $description->appendValue($this->expectedCode);
    }

    private function getExample($exampleName) {
        $localFiles = new Local(__DIR__ . '/../examples/');
        $examples = new Filesystem($localFiles);
        $example = $this->removeWhiteSpace($examples->read($exampleName));
        return substr($example, 5);
    }

    private function removeWhiteSpace($code) {
        return preg_replace('/\s+/', '', $code);
    }
}
