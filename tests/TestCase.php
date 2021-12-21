<?php

namespace Elliottlawson\LaravelClosureDependencyInjector\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Elliottlawson\LaravelClosureDependencyInjector\LaravelClosureDependencyInjectorServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Elliottlawson\\LaravelClosureDependencyInjector\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelClosureDependencyInjectorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-closure-dependency-injector_table.php.stub';
        $migration->up();
        */
    }
}
