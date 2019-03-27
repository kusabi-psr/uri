<?php

namespace Tests\Unit\Exceptions;

use Kusabi\Psr\Exceptions\InvalidUriException;
use PHPUnit\Framework\TestCase;

class InvalidUriExceptionTest extends TestCase
{
    public function testMessageContainsUri()
    {
        $this->expectException(InvalidUriException::class);
        $this->expectExceptionMessage("The uri 'test/url' is not valid");
        throw new InvalidUriException('test/url');
    }
}
