[![Release Badge](https://img.shields.io/github/release/kusabi-psr/uri.svg)](https://img.shields.io/github/release/kusabi-psr/uri.svg)
[![Tag Badge](https://img.shields.io/github/tag/kusabi-psr/uri.svg)](https://img.shields.io/github/tag/kusabi-psr/uri.svg)
[![Coverage Badge](https://img.shields.io/codacy/coverage/a2236972c0084da8a41a880cb7e017b8.svg)](https://img.shields.io/codacy/grade/bec9194f88a843fd9abd4edef6102f9b.svg)
[![Grade Badge](https://img.shields.io/codacy/grade/a2236972c0084da8a41a880cb7e017b8.svg)](https://img.shields.io/codacy/grade/bec9194f88a843fd9abd4edef6102f9b.svg)
[![Issues Badge](https://img.shields.io/github/issues/kusabi-psr/uri.svg)](https://img.shields.io/github/issues/kusabi-psr/uri.svg)
[![Licence Badge](https://img.shields.io/github/license/kusabi-psr/uri.svg)](https://img.shields.io/github/license/kusabi-psr/uri.svg)
[![Code Size](https://img.shields.io/github/languages/code-size/kusabi-psr/uri.svg)](https://img.shields.io/github/languages/code-size/kusabi-psr/uri.svg)

An implementation of a [PSR-7](https://www.php-fig.org/psr/psr-7/) & [PSR-17](https://www.php-fig.org/psr/psr-17/) conforming Uri library

# Using the Uri class

The Uri class is a very basic wrapper around a Uri string.


```php
use kusabi-psr\Psr\Uri;

// Instantiate a Uri instance
$uri = new Uri('https://user:pass@www.my-site.com:8080/users/22?filter=name#bottom');

// Fetch the properties of the Uri instance
echo $uri->getScheme();
echo $uri->getAuthority();
echo $uri->getUserInfo();
echo $uri->getHost();
echo $uri->getPort();
echo $uri->getPath();
echo $uri->getQuery();
echo $uri->getFragment();
```


# Using the Uri Factory

The Uri factory can be used to create the Uri instance too.


```php
use kusabi-psr\Psr\UriFactory;

// Instantiate a Uri instance
$factory = new UriFactory();
$uri = $factory->createUri('https://user:pass@www.my-site.com:8080/users/22?filter=name#bottom');

// Fetch the properties of the Uri instance
echo $uri->getScheme();
echo $uri->getAuthority();
echo $uri->getUserInfo();
echo $uri->getHost();
echo $uri->getPort();
echo $uri->getPath();
echo $uri->getQuery();
echo $uri->getFragment();
```
