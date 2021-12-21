# A flexible closure dependency injector

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elliottlawson/laravel-closure-dependency-injector.svg?style=flat-square)](https://packagist.org/packages/elliottlawson/laravel-closure-dependency-injector)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/elliottlawson/laravel-closure-dependency-injector/run-tests?label=tests)](https://github.com/elliottlawson/laravel-closure-dependency-injector/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/elliottlawson/laravel-closure-dependency-injector/Check%20&%20fix%20styling?label=code%20style)](https://github.com/elliottlawson/laravel-closure-dependency-injector/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elliottlawson/laravel-closure-dependency-injector.svg?style=flat-square)](https://packagist.org/packages/elliottlawson/laravel-closure-dependency-injector)

This is a flexible (and highly extensible) simple (non-recursive) dependency injector for closures.

- The engine will loop over all arguments passed to the closure, attempt to instantiate them and inject them back in.
- The process is not recursive (sub-dependencies are not instantiated)

## Installation

You can install the package via composer:

```bash
composer require elliottlawson/laravel-closure-dependency-injector
```

## Usage

```php
use Elliottlawson\LaravelClosureDependencyInjector\DependencyEngine;

class Writer
{
    public function output(string $message): string
    {
        return echo $message;
    }
}

DependencyEngine::handle(function (Writer $writer) {
    $writer->output('hello world');
})

// hello world
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Elliott Lawson](https://github.com/elliottlawson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
