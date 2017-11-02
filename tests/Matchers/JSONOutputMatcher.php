<?php

namespace Elsevier\JSONSchemaPHPGenerator\Tests\Matchers;

use Hamcrest\BaseMatcher;
use Hamcrest\Description;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class JSONOutputMatcher extends BaseMatcher
{
    private $expectedString;

    public function __construct($expectedString)
    {
        $this->expectedString = $this->removeWhiteSpace($expectedString);
    }

    public function matches($generatedJSON)
    {
        return $generatedJSON === $this->expectedString;
    }

    public function describeTo(Description $description)
    {
        $description->appendText('Generated JSON to equal the expected sample JSON ');
        $description->appendValue($this->expectedString);
    }

    private function removeWhiteSpace($code)
    {
        return preg_replace('/\s+/', '', $code);
    }
}
