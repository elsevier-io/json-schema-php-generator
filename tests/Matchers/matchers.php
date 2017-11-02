<?php

use Elsevier\JSONSchemaPHPGenerator\Tests\Matchers\GeneratedCodeExampleMatcher;
use Elsevier\JSONSchemaPHPGenerator\Tests\Matchers\JSONOutputMatcher;

function hasClassThatMatchesTheExample($filename)
{
    return new GeneratedCodeExampleMatcher($filename);
}

function matchesJSONOutput($string)
{
    return new JSONOutputMatcher($string);
}
