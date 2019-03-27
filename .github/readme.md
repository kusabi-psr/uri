An implementation of a [PSR-7](https://www.php-fig.org/psr/psr-7/) & [PSR-17](https://www.php-fig.org/psr/psr-17/) conforming Uri library

# Using the Uri class

The Uri class is a very basic wrapper around a Uri string.


```php
use Kusabi\Psr\Uri;

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
