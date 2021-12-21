<?php

namespace Elliottlawson\LaravelClosureDependencyInjector;

use Elliottlawson\LaravelClosureDependencyInjector\Commands\LaravelClosureDependencyInjectorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelClosureDependencyInjectorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-closure-dependency-injector')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-closure-dependency-injector_table')
            ->hasCommand(LaravelClosureDependencyInjectorCommand::class);
    }
}
