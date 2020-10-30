# Uri wrapper

![Tests](https://github.com/kusabi/uri/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/kusabi/uri/branch/master/graph/badge.svg)](https://codecov.io/gh/kusabi/dot)
[![Licence Badge](https://img.shields.io/github/license/kusabi/uri.svg)](https://img.shields.io/github/license/kusabi/uri.svg)
[![Release Badge](https://img.shields.io/github/release/kusabi/uri.svg)](https://img.shields.io/github/release/kusabi/uri.svg)
[![Tag Badge](https://img.shields.io/github/tag/kusabi/uri.svg)](https://img.shields.io/github/tag/kusabi/uri.svg)
[![Issues Badge](https://img.shields.io/github/issues/kusabi/uri.svg)](https://img.shields.io/github/issues/kusabi/uri.svg)
[![Code Size](https://img.shields.io/github/languages/code-size/kusabi/uri.svg?label=size)](https://img.shields.io/github/languages/code-size/kusabi/uri.svg)

<sup>An implementation of a [PSR-7](https://www.php-fig.org/psr/psr-7/) & [PSR-17](https://www.php-fig.org/psr/psr-17/) conforming Uri library</sup>

## Installation

Installation is simple using composer.

```bash
composer require kusabi/uri
```

Or simply add it to your `composer.json` file

```json
{
    "require": {
        "kusabi/uri": "^1.0"
    }
}
```

## Using the Uri class

The Uri class is a very basic wrapper around a Uri string.


```php
use Kusabi\Uri\Uri;

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


## Using the Uri factory

The Uri factory can be used to create the Uri instance too.


```php
use Kusabi\Uri\UriFactory;

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
