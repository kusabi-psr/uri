<?php

namespace Tests\Unit;

use Kusabi\Uri\Exceptions\InvalidUriException;
use Kusabi\Uri\Uri;
use Kusabi\Uri\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactoryTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(UriFactoryInterface::class, new UriFactory());
    }

    public function testCreatesInstanceOfUri()
    {
        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri('/users/1');
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);
    }

    public function testThrowsExceptionIfUriIsMalformed()
    {
        $this->expectException(InvalidUriException::class);
        $uriFactory = new UriFactory();
        $uriFactory->createUri('host:65536');
    }
}
