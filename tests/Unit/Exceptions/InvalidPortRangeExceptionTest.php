<?php

namespace Tests\Unit\Exceptions;

use Kusabi\Psr\Exceptions\InvalidPortRangeException;
use PHPUnit\Framework\TestCase;

class InvalidPortRangeExceptionTest extends TestCase
{
    public function testMessageContainsPort()
    {
        $this->expectException(InvalidPortRangeException::class);
        $this->expectExceptionMessage("Port '60000' is not within a valid UDP/TCP range");
        throw new InvalidPortRangeException(60000);
    }
}
