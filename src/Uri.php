<?php

namespace Kusabi\Psr;

use Kusabi\Psr\Exceptions\InvalidPortRangeException;
use Kusabi\Psr\Exceptions\InvalidUriException;
use Psr\Http\Message\UriInterface;

/**
 * A Uri wrapper class implementation that conforms to PSR-7
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 *
 * @see UriInterface
 */
class Uri implements UriInterface
{
    /**
     * The smallest allowed port range
     *
     * @var int
     */
    const PORT_MIN = 0;

    /**
     * The largest allowed port range
     *
     * @var int
     */
    const PORT_MAX = 65535;

    /**
     * A list of common ports for schemes
     *
     * @var array
     */
    const SCHEME_PORTS = [
        'ftp' => 21,
        'ssh' => 22,
        'telnet' => 23,
        'smtp' => 25,
        'dns' => 53,
        'tftp' => 69,
        'http' => 80,
        'sftp' => 115,
        'https' => 443,
    ];

    /**
     * The uri scheme
     *
     * @var string
     */
    protected $scheme;

    /**
     * The uri host
     *
     * @var string
     */
    protected $host;

    /**
     * The uri port
     *
     * @var string
     */
    protected $port;

    /**
     * The uri user
     *
     * @var string
     */
    protected $user;

    /**
     * The uri password
     *
     * @var string
     */
    protected $password;

    /**
     * The uri path
     *
     * @var string
     */
    protected $path;

    /**
     * The uri query string
     *
     * @var string
     */
    protected $query;

    /**
     * The uri fragment
     *
     * @var string
     */
    protected $fragment;

    /**
     * Uri constructor.
     *
     * @param string $uri
     *
     * @throws InvalidUriException if the URI is malformed
     */
    public function __construct(string $uri = '')
    {
        // Parse the URI
        $parsed = parse_url($uri);

        // Was the URI malformed?
        if ($parsed === false) {
            throw new InvalidUriException($uri);
        }

        // Fetch the values
        $this->scheme = $parsed['scheme'] ?? '';
        $this->host = $parsed['host'] ?? '';
        $this->port = $parsed['port'] ?? '';
        $this->user = $parsed['user'] ?? '';
        $this->password = $parsed['pass'] ?? '';
        $this->path = $parsed['path'] ?? '';
        $this->query = $parsed['query'] ?? '';
        $this->fragment = $parsed['fragment'] ?? '';
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::__toString()
     */
    public function __toString()
    {
        // Get the path
        $path = $this->getPath();

        // Path Rule #1: If the path is rootless and an authority is present, the path MUST be prefixed by “/“
        if ($path && $this->getAuthority() && substr($path, 0, 1) != '/') {
            $path = '/'.$path;
        }

        // Path Rule #2: If the path is starting with more than one “/” and no authority is present, the starting slashes MUST be reduced to one.
        if ($path && !$this->getAuthority() && substr($path, 0, 2) == '//') {
            $path = '/'.ltrim($path, '/');
        }

        return implode('', array_filter([
            $this->getScheme() ? $this->getScheme().':' : null,
            $this->getAuthority() ? '//'.$this->getAuthority() : null,
            $path,
            $this->getQuery() ? '?'.$this->getQuery() : null,
            $this->getFragment() ? '#'.$this->getFragment() : null,
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getScheme()
     */
    public function getScheme()
    {
        return strtolower($this->scheme);
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getAuthority()
     */
    public function getAuthority()
    {
        // Return blank if there is no host info
        if (!$this->getHost()) {
            return '';
        }

        // Join the user-info, host and port info together, but leave user-info and port off if it was not set
        return implode('', array_filter([
            $this->getUserInfo() ? $this->getUserInfo().'@' : null,
            $this->getHost(),
            $this->getPort() ? ':'.$this->getPort() : null,
        ]));
    }

    /**
     * Get the username from the URI
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the password from the URI
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getUserInfo()
     */
    public function getUserInfo()
    {
        // Return blank if there is no user info
        if (!$this->getUser()) {
            return '';
        }

        // Join the user and password info together, but leave password off if it was not set
        return implode(':', array_filter([
            $this->getUser(),
            $this->getPassword()
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getHost()
     */
    public function getHost()
    {
        return strtolower($this->host);
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getPort()
     */
    public function getPort()
    {
        // No port set
        if (!$this->getPortValue()) {
            return null;
        }

        // Get the default for the scheme
        $default = $this->getStandardPortForScheme($this->getScheme());

        // If no default port found OR if default port and actual port DO NOT match then return the port value
        if (!$default || $default != $this->getPortValue()) {
            return (int) $this->getPortValue();
        }

        // return null as fallback
        return null;
    }

    /**
     * Get the value fo the supplied port
     *
     * @return string
     */
    public function getPortValue()
    {
        return $this->port;
    }

    /**
     * Get standard port number for the supplied scheme
     * Returns null if scheme is not known
     *
     * @param string $scheme
     *
     * @return int|null
     */
    public function getStandardPortForScheme(string $scheme)
    {
        return self::SCHEME_PORTS[strtolower($scheme)] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getPath()
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getQuery()
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getFragment()
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withScheme()
     */
    public function withScheme($scheme)
    {
        $result = clone $this;
        $result->scheme = $scheme;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withUserInfo()
     */
    public function withUserInfo($user, $password = null)
    {
        $result = clone $this;
        $result->user = $user;
        $result->password = (string) $password;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withHost()
     */
    public function withHost($host)
    {
        $result = clone $this;
        $result->host = $host;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withPort()
     */
    public function withPort($port)
    {
        if ($port < self::PORT_MIN || $port > self::PORT_MAX) {
            throw new InvalidPortRangeException((string) $port);
        }
        $result = clone $this;
        $result->port = $port ? (string) $port : null;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withPath()
     */
    public function withPath($path)
    {
        $result = clone $this;
        $result->path = $path;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withQuery()
     */
    public function withQuery($query)
    {
        $result = clone $this;
        $result->query = $query;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withFragment()
     */
    public function withFragment($fragment)
    {
        $result = clone $this;
        $result->fragment = $fragment;
        return $result;
    }
}
