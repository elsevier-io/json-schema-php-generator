<?php

use Elsevier\JSONSchemaPHPGenerator\Tests\Matchers\GeneratedCodeExampleMatcher;

function hasClassThatMatchesTheExample($filename)
{
    return new GeneratedCodeExampleMatcher($filename);
}
