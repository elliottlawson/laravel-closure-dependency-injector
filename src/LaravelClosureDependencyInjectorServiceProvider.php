<?php

namespace Elliottlawson\LaravelClosureDependencyInjector;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelClosureDependencyInjectorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-closure-dependency-injector');
    }
}
