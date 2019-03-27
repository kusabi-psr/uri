<?php

namespace Tests\Unit;

use Kusabi\Psr\Exceptions\InvalidPortRangeException;
use Kusabi\Psr\Exceptions\InvalidUriException;
use Kusabi\Psr\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * RFC 3986
     * Section 3.1
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     */
    public function testSchemeMustReturnLowerCase()
    {
        $uri = new Uri('HTTPS://www.example.com');
        $this->assertSame('https', $uri->getScheme());
    }

    /**
     * RFC 3986
     * Section 3.2.2
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     */
    public function testHostMustReturnLowerCase()
    {
        $uri = new Uri('http://WwW.EXAMPLE.com');
        $this->assertSame('www.example.com', $uri->getHost());
    }

    public function testUserInfoMustReturnEmptyStringIfNonePresent()
    {
        $uri = new Uri('http://www.example.com/users');
        $this->assertSame('', $uri->getUserInfo());
    }

    public function testUserInfoShouldReturnEmptyIfNoUserIsSet()
    {
        $uri = new Uri('http://:pass@www.example.com/users');
        $this->assertSame('', $uri->getUserInfo());
    }

    public function testUserInfoShouldReturnIfJustUserIsSet()
    {
        $uri = new Uri('http://user@www.example.com/users');
        $this->assertSame('user', $uri->getUserInfo());
    }

    public function testUserInfoShouldReturnPasswordWhenSet()
    {
        $uri = new Uri('http://user:pass@www.example.com/users');
        $this->assertSame('user:pass', $uri->getUserInfo());
    }

    /**
     * RFC 3986
     * Section 3.2
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     */
    public function testAuthorityMustReturnEmptyIfNonePresent()
    {
        $uri = new Uri('users');
        $this->assertSame('', $uri->getAuthority());
    }

    /**
     * RFC 3986
     * Section 3.2
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     */
    public function testAuthorityShouldReturnHostIfSet()
    {
        $uri = new Uri('http://www.example.com');
        $this->assertSame('www.example.com', $uri->getAuthority());
    }

    /**
     * RFC 3986
     * Section 3.2
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     */
    public function testAuthorityShouldReturnUserInfoIfSet()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertSame('user@www.example.com', $uri->getAuthority());

        $uri = new Uri('http://user:pass@www.example.com');
        $this->assertSame('user:pass@www.example.com', $uri->getAuthority());
    }

    /**
     * RFC 3986
     * Section 3.2
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     */
    public function testAuthorityShouldReturnPortIfSet()
    {
        $uri = new Uri('http://user@www.example.com:1234');
        $this->assertSame('user@www.example.com:1234', $uri->getAuthority());

        $uri = new Uri('http://user:pass@www.example.com:1234');
        $this->assertSame('user:pass@www.example.com:1234', $uri->getAuthority());
    }

    /**
     * RFC 3986
     * Section 3.2
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     */
    public function testAuthorityShouldNotShowPortIfItIsStandardForScheme()
    {
        $uri = new Uri('http://user@www.example.com:80');
        $this->assertSame('user@www.example.com', $uri->getAuthority());

        $uri = new Uri('https://user@www.example.com:443');
        $this->assertSame('user@www.example.com', $uri->getAuthority());
    }

    public function testPortReturnsIntegerForNonStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:8080');
        $this->assertSame(8080, $uri->getPort());
    }

    public function testPortShouldReturnNullForStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:80');
        $this->assertNull($uri->getPort());

        $uri = new Uri('https://user@www.example.com:443');
        $this->assertNull($uri->getPort());
    }

    public function testPortReturnsNullWhenNotSet()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertNull($uri->getPort());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public function testPathCanBeEmpty()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertSame('', $uri->getPath());

        $uri = new Uri('');
        $this->assertSame('', $uri->getPath());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public function testPathCanBeRoot()
    {
        $uri = new Uri('http://user@www.example.com/user/index');
        $this->assertSame('/user/index', $uri->getPath());

        $uri = new Uri('/user/index');
        $this->assertSame('/user/index', $uri->getPath());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public function testPathCanBeRelative()
    {
        $uri = new Uri('user/index');
        $this->assertSame('user/index', $uri->getPath());

        $uri = new Uri('user/index');
        $this->assertSame('user/index', $uri->getPath());
    }

    /**
     * @todo test for this...
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public function testPathMustBePercentEncoded()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testQueryMustBeEmptyWhenNotSet()
    {
        $uri = new Uri('');
        $this->assertSame('', $uri->getQuery());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testQueryReturned()
    {
        $uri = new Uri('index.php?a=b&c[1]=d');
        $this->assertSame('a=b&c[1]=d', $uri->getQuery());
    }

    /**
     * @todo test for this...
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testQueryMustBePercentEncoded()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testFragmentMustBeEmptyWhenNotSet()
    {
        $uri = new Uri('');
        $this->assertSame('', $uri->getFragment());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testFragmentReturned()
    {
        $uri = new Uri('index.php#top');
        $this->assertSame('top', $uri->getFragment());
    }

    /**
     * @todo test for this...
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function testFragmentMustBePercentEncoded()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testWithSchemeKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com');
        $original->withScheme('https');
        $this->assertSame('http', $original->getScheme());
    }

    public function testWithSchemeChangesScheme()
    {
        $original = new Uri('http://www.example.com');
        $this->assertSame('https', $original->withScheme('https')->getScheme());
    }

    public function testWithSchemeIsCaseInsensitive()
    {
        $original = new Uri('http://www.example.com');
        $this->assertSame('https', $original->withScheme('HTTPS')->getScheme());
    }

    public function testWithUserInfoKeepsOriginalIntact()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $original->withUserInfo('userB', 'passB');
        $this->assertSame('user', $original->getUser());
        $this->assertSame('pass', $original->getPassword());
        $this->assertSame('user:pass', $original->getUserInfo());
    }

    public function testWithUserInfoChangesUserInfo()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('userB', 'passB');
        $this->assertSame('userB', $changed->getUser());
        $this->assertSame('passB', $changed->getPassword());
        $this->assertSame('userB:passB', $changed->getUserInfo());
    }

    public function testWithUserPassCanBeRemoved()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('userB');

        $this->assertSame('userB', $changed->getUser());
        $this->assertSame('', $changed->getPassword());
        $this->assertSame('userB', $changed->getUserInfo());
    }

    public function testWithUserUserCanBeRemoved()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('');
        $this->assertSame('', $changed->getUser());
        $this->assertSame('', $changed->getPassword());
        $this->assertSame('', $changed->getUserInfo());
    }

    public function testWithHostKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com');
        $original->withHost('test.co.uk');
        $this->assertSame('www.example.com', $original->getHost());
    }

    public function testWithHostChangesHost()
    {
        $original = new Uri('http://www.example.com');
        $changed = $original->withHost('test.co.uk');
        $this->assertSame('test.co.uk', $changed->getHost());
    }

    public function testWithHostHostCanBeRemoved()
    {
        $original = new Uri('http://www.example.com');
        $changed = $original->withHost('');
        $this->assertSame('', $changed->getHost());
    }

    public function testWithPortKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com:8080');
        $original->withPort(1234);
        $this->assertSame(8080, $original->getPort());
    }

    public function testWithPortChangesPort()
    {
        $original = new Uri('http://www.example.com:8080');
        $changed = $original->withPort(1234);
        $this->assertSame(1234, $changed->getPort());
    }

    public function testWithPortPortCanBeRemoved()
    {
        $original = new Uri('http://www.example.com:8080');
        $changed = $original->withPort(null);
        $this->assertNull($changed->getPort());
    }

    public function testWithPortThrowsExceptionWhenBelowRange()
    {
        $this->expectException(InvalidPortRangeException::class);
        $original = new Uri('http://www.example.com');
        $original->withPort(-1);
    }

    public function testWithPortThrowsExceptionWhenAboveRange()
    {
        $this->expectException(InvalidPortRangeException::class);
        $original = new Uri('http://www.example.com');
        $original->withPort(65536);
    }

    public function testWithPathKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com/example');
        $original->withPath('test');
        $this->assertSame('/example', $original->getPath());
    }

    public function testWithPathChangesPath()
    {
        $original = new Uri('http://www.example.com/example');
        $changed = $original->withPath('test');
        $this->assertSame('test', $changed->getPath());
    }

    public function testWithQueryKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com?a=b');
        $original->withQuery('a[0]=b&a[1]=c');
        $this->assertSame('a=b', $original->getQuery());
    }

    public function testWithQueryChangesQuery()
    {
        $original = new Uri('http://www.example.com?a=b');
        $changed = $original->withQuery('a[0]=b&a[1]=c');
        $this->assertSame('a[0]=b&a[1]=c', $changed->getQuery());
    }

    public function testWithQueryQueryCanBeRemoved()
    {
        $original = new Uri('http://www.example.com?a=b');
        $changed = $original->withQuery('');
        $this->assertSame('', $changed->getQuery());
    }

    public function testWithFragmentKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com#top');
        $original->withFragment('bottom');
        $this->assertSame('top', $original->getFragment());
    }

    public function testWithFragmentChangesQuery()
    {
        $original = new Uri('http://www.example.com#top');
        $changed = $original->withFragment('bottom');
        $this->assertSame('bottom', $changed->getFragment());
    }

    public function testWithFragmentFragmentCanBeRemoved()
    {
        $original = new Uri('http://www.example.com#top');
        $changed = $original->withFragment('');
        $this->assertSame('', $changed->getFragment());
    }

    public function testFullUri()
    {
        $uri = new Uri('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top');
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('user', $uri->getUser());
        $this->assertSame('pass', $uri->getPassword());
        $this->assertSame('user:pass', $uri->getUserInfo());
        $this->assertSame('www.example.com', $uri->getHost());
        $this->assertSame(8080, $uri->getPort());
        $this->assertSame('/users/3', $uri->getPath());
        $this->assertSame('a=b&c[1]=d', $uri->getQuery());
        $this->assertSame('top', $uri->getFragment());
    }

    public function testToStringWithFullUri()
    {
        $uri = new Uri('');
        $uri = $uri->withScheme('https')
            ->withUserInfo('user', 'pass')
            ->withHost('www.example.com')
            ->withPort(8080)
            ->withPath('/users/3')
            ->withQuery('a=b&c[1]=d')
            ->withFragment('top');
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', (string) $uri);
    }

    public function testToStringWithRootlessPathAndAuthority()
    {
        $uri = new Uri('');
        $uri = $uri->withScheme('https')
            ->withUserInfo('user', 'pass')
            ->withHost('www.example.com')
            ->withPort(8080)
            ->withPath('users/3')
            ->withQuery('a=b&c[1]=d')
            ->withFragment('top');
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', (string) $uri);
    }

    public function testToStringWithoutAuthorityAndMultipleStartingSlashes()
    {
        $uri = new Uri('');
        $uri = $uri->withPath('////users/3');
        $this->assertSame('/users/3', (string) $uri);
    }

    public function testVariousToStringOutputs()
    {
        $uri = new Uri('');
        $this->assertSame('', (string) $uri);

        $uri = $uri->withPath('help');
        $this->assertSame('help', (string) $uri);

        $uri = $uri->withHost('example.com');
        $this->assertSame('//example.com/help', (string) $uri);

        $uri = $uri->withScheme('ftp');
        $this->assertSame('ftp://example.com/help', (string) $uri);

        $uri = $uri->withPort(999);
        $this->assertSame('ftp://example.com:999/help', (string) $uri);
    }

    public function testThrowsExceptionIfUriIsMalformed()
    {
        $this->expectException(InvalidUriException::class);
        new Uri('host:65536');
    }
}
