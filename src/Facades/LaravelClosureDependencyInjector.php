<?php

namespace Elliottlawson\LaravelClosureDependencyInjector\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elliottlawson\LaravelClosureDependencyInjector\LaravelClosureDependencyInjector
 */
class LaravelClosureDependencyInjector extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-closure-dependency-injector';
    }
}
