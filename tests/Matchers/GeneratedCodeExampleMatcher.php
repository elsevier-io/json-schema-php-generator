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

    public function matches($generatedJSON)
    {
        assertThat($generatedJSON, hasKey($this->className));
        $actualCode = $this->removeWhiteSpace($generatedJSON[$this->className]);
        return $actualCode === $this->expectedCode;
    }

    public function describeTo(Description $description)
    {
        $description->appendText('Generated code to equal the expected sample code ');
        $description->appendValue($this->expectedCode);
    }

    public function describeMismatch($item, Description $description)
    {
        $actualText = $this->removeWhiteSpace($item[$this->className]);
        $char = 0;
        while ($char < strlen($this->expectedCode) && $char < strlen($actualText)) {
            if ($actualText[$char] !== $this->expectedCode[$char]) {
                break;
            }
            $char++;
        }
        $mismatchedText = substr($actualText, $char, 50);
        $description->appendText('differs at ')->appendValue($mismatchedText);
        $description->appendText("\nactual text ")->appendValue($actualText);
    }

    private function getExample($exampleName)
    {
        $localFiles = new Local(__DIR__ . '/../examples/');
        $examples = new Filesystem($localFiles);
        $example = $this->removeWhiteSpace($examples->read($exampleName));
        return substr($example, 5);
    }

    private function removeWhiteSpace($code)
    {
        return preg_replace('/\s+/', '', $code);
    }
}
