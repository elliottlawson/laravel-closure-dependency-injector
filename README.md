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

## Usage Examples

```php
use Elliottlawson\LaravelClosureDependencyInjector\DependencyEngine;

class Writer
{
    public function output(string $message): string
    {
        return echo $message;
    }
}

// Immediately execute closure
DependencyEngine::handle(function (Writer $writer) {
    $writer->output('hello world');
});


// - or -


// Execute deferred
$engine = DependencyEngine::handle(
    function (Writer $writer) {
        $writer->output('hello world');
    },
    false,
);

$engine->runClosure();
```

### More...
Another way this can be used is to provide a cleaner interface, where more complex steps can be hidden behind the scenes

Say you had some configs you wanted to set in a single streamlined step...
```php
$user = User::first();

$user->updateConfigs(function(ConfigOne $one, ConfigTwo $two) {
    $one = 'Hello';
    $two = ['one', 'two', 'three'];
});
```
<br/>

This type of functionality could be enabled by manually setting up the injection process
```php
use Elliottlawson\LaravelClosureDependencyInjector\DependencyEngine;

trait ConfigHelper
{
    public function updateConfigs(Closure $callback): static
    {
        // Resolve instances for each of the callback's dependencies
        $arguments = DependencyEngine::resolveArguments(
            callback: $callback,
            instance: ConfigBase::class,
            parameter: $this,
        );
        
        // Run the closure
        $callback(...$arguments);
        
        // Perform after-the-fact operations
        $this->persistConfigs($arguments);
    }
    
    protected function persistConfigs(array $configs)
    {
        collect($configs)
            ->each(fn ($config) => $config->save());
    }
}
```



## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
