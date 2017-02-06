# tokens

[![Build Status](https://img.shields.io/travis/alt3/tokens/master.svg?style=flat-square)](https://travis-ci.org/alt3/tokens)
[![StyleCI Status](https://styleci.io/repos/80359487/shield)](https://styleci.io/repos/80359487)
[![HHVM](https://img.shields.io/hhvm/neomerx/json-api.svg?style=flat-square)](https://travis-ci.org/neomerx/json-api)
[![Coverage Status](https://img.shields.io/codecov/c/github/alt3/tokens/master.svg?style=flat-square)](https://codecov.io/github/alt3/tokens)
[![Total Downloads](https://img.shields.io/packagist/dt/alt3/tokens.svg?style=flat-square)](https://packagist.org/packages/alt3/tokens)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)


The framework agnostic PHP library for generating (secure) token objects.

## What does it do?

At some point every application has to deal with tokens and the two distinct
areas that come with it:

1. generating the (secure) tokens
2. handling the business logic around them

This library offers a drop-in solution for the first area by introducing
standardized `Token` objects ready for use in your application, basically
saving you a lot of work and allowing you to focus on what matters most;
the business logic.

Because the library follows the Adapter Pattern you can easily create your
own token-specific generator if the included ones don't meet your requirements.
## Requirements

* PHP >=5.6 / HHVM

## Installation

1. Install the plugin using composer:

    ```bash
    composer require alt3/tokens
    ```

## Usage

### Token Objects

This main responsibility of this library is producing `Token` objects similar
to the one shown below. Because of the standardized structure these objects
are capable of storing any kind of token ranging from (OTP) passwords to
SMS pin codes, coupon codes and JSON Web Tokens (JWT).

```php
Alt3\Tokens\RandomBytesToken Object
(
    [adapter:protected] => Alt3\Tokens\Adapters\RandomBytesAdapter Object
        (
            [length:protected] => 32
            [function:protected] => Array
                (
                    [PHP 5.x native] => openssl_random_pseudo_bytes
                )

        )

    [token:protected] => 2affc51354bf23ac634bac894a1c38db
    [category:protected] => password-reset
    [payload:protected] => 
    [lifetime:protected] => +3 days
    [created:protected] => DateTimeImmutable Object
        (
            [date] => 2017-02-06 13:53:25.000000
            [timezone_type] => 3
            [timezone] => UTC
        )

    [expires:protected] => DateTimeImmutable Object
        (
            [date] => 2017-02-09 13:53:25.000000
            [timezone_type] => 3
            [timezone] => UTC
        )
)
```

### Using the RandomBytesAdapter

Use the RandomBytesAdapter to generate cryptographically secure tokens suitable
for e.g. One Time Passwords (OTP), salts, keys or initialization vectors.

> PHP 5.x users are strongly advised to install the
> [paragonie/random_compat](https://github.com/paragonie/random_compat)
> polyfill composer package to ensure generated tokens are truly secure.

To use :

1. include the `RandomBytes` convenience class
2. instantiate an object (automatically generates the token)
3. optionally alter the token object using one of the supported methods 

```php
use Alt3\Tokens\RandomBytesToken;

$token = new RandomBytesToken(); 
print $token->getToken(); // b96b7826c75485b10518a36e2ca94860

$token = new RandomBytesToken(12); // 2109028d9bac

$token = new RandomBytesToken(12, true); // ����Wz

$token->setLifetime('+30 minutes');
print $token->getExpires(); // DateTimeImmutable

$token->setCategory('password-reset');

$token->setPayload('{"email": "joe@example.com"}');
```

### Using the RandomIntAdapter

Use the RandomIntAdapter to generate cryptographically random integers
that are suitable for e.g. SMS pin codes.

> PHP 5.x users are strongly advised to install the
> [paragonie/random_compat](https://github.com/paragonie/random_compat)
> polyfill composer package to ensure generated tokens are truly secure.

```php
use Alt3\Tokens\RandomIntToken;

$token = new RandomIntToken(0, 10);
print $token->getToken(); // 6

$token = new RandomIntToken(100000, 999999); // 625048
```

### Using the ManualAdapter

The ManualAdapter allows you to generate a `Token` object using a 
token value you already have. Useful for e.g. coupon codes or representing
JWT tokens as JSON API resources.

```php
use Alt3\Tokens\ManualToken;

$token = new ManualToken('SPRING2017');
print $token->getToken(); // SPRING2017
```
    
### Token Methods

Getter methods:
 
- `getToken()`: retrieves the `token` property (string)
- `getCategory()`: retrieves the `category` property (string)
- `getLifetime()`: retrieves the `lifetime` property ([DateTime::modify](http://php.net/manual/en/datetime.modify.php) supported string)
- `getPayload()`: retrieves the `payload` property (mixed)
- `getCreated()`: retrieves the `created` property ([DateTimeImmutable](http://php.net/manual/en/class.datetimeimmutable.php))
- `getExpires()`: retrieves the `expires` property ([DateTimeImmutable](http://php.net/manual/en/class.datetimeimmutable.php))
- `toArray()`: returns an array containing all token properties and their content

Setter methods:

- `setCategory()`: sets the `category` property (string)
- `setPayload()`: sets the `payload` property (string, arrays, objects, etc.)
- `setLifetime()`: sets the `lifetime` property AND updates the `expires` property ([DateTime::modify](http://php.net/manual/en/datetime.modify.php) supported string)

## Creating Custom Adapters

To start generating your own custom tokens simply create a custom Adapter
similar to the one shown below.

```php
namespace App\Adapters;

use Alt3\Tokens\Adapters\AdapterInterface;

class CustomAdapter implements AdapterInterface
{
    protected $argument;

    // Use the constructor to pass arguments to the token generator
    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    public function generate()
    {
        return 'custom-token-with-' . $this->argument;
    }
}
```

Now generate your own tokens using:

```php
use App\Adapters\CustomAdapter;

$token = new Token(new CustomAdapter('spice'));
print $token->getToken(); // custom-token-with-spice
```

> Feel free to PR your Adapter if you think others could find it useful. 

## Contribute

Before submitting a PR make sure:

- [PHPUnit](http://book.cakephp.org/3.0/en/development/testing.html#running-tests)
tests pass (`composer run-script tests`)
- [PSR-2 Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
 tests pass (`composer run-script cs`)
- [Coveralls Code Coverage ](https://coveralls.io/github/alt3/tokens) remains at 100%
