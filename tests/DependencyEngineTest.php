<?php

use Elliottlawson\LaravelClosureDependencyInjector\DependencyEngine;
use Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\AbstractDependency;
use Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\DependencyOne;
use Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\DependencyTwo;
use Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\DependentDependency;
use Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\StandAloneDependency;

it('can resolve dependencies', function () {
    $closure = function (DependencyOne $one, DependencyTwo $two) {};

    $arguments = DependencyEngine::resolveArguments($closure);

    expect($arguments)->toHaveCount(2);

    [$dependency_one, $dependency_two] = $arguments;

    expect($dependency_one)
        ->toBeInstanceOf(DependencyOne::class)
        ->run()->toBe('success');

    expect($dependency_two)
        ->toBeInstanceOf(DependencyTwo::class)
        ->run()->toBe('success');
});

it('throws an exception for non type hinted arguments', function () {
    DependencyEngine::resolveArguments(function (DependencyOne $one, $two) {});
})->throws(RuntimeException::class, 'Arguments must be type hinted');

it('throws_an_exceptions_for_non_instantiable_arguments', function () {
    DependencyEngine::resolveArguments(function (DependencyOne $one, string $string = 'Kids, don\'t try this at home') {});
})->throws(RuntimeException::class, 'Argument is not instantiable. Variables should be passed via use');

it('can constrain_dependencies instances and throw and exception', function () {
    DependencyEngine::resolveArguments(
        function (DependencyOne $one, StandAloneDependency $standAlone) {},
        AbstractDependency::class,
    );
})->throws(RuntimeException::class, 'Arguments are required to be instances of Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers\AbstractDependency');

it('can properly use its abstraction method handle - with auto run', function () {
    $preset_variable = 'default value';

    // We do this in order to verify if the closure ran successfully
    $closure = function (DependencyOne $one) use (&$preset_variable) {
        $preset_variable = 'modified by closure';
    };

    // Auto run is on by default
    DependencyEngine::handle($closure);

    expect($preset_variable)->toBe('modified by closure');
});

it('can properly use its abstraction method handle - without auto run', function () {
    $preset_variable = 'default value';

    // We do this in order to verify if the closure ran successfully
    $closure = function (DependencyOne $one) use (&$preset_variable) {
        $preset_variable = 'modified by closure';
    };

    // Auto run is on by default
    $engine = DependencyEngine::handle($closure, false);

    // Closure should not have run yet
    expect($preset_variable)->toBe('default value');

    $engine->runClosure();

    // Now, the closure should have updated the variable
    expect($preset_variable)->toBe('modified by closure');
});

it('can pass a parameter to the dependencies', function () {
    $sub_dependency = new StandAloneDependency();

    $arguments = DependencyEngine::resolveArguments(
        function (DependentDependency $dependency) {},
        null, // no specific instance constraint
        $sub_dependency,
    );

    $resolved = $arguments[0];

    expect($resolved)
        ->toBeInstanceOf(DependentDependency::class)
        ->dependency->toBeInstanceOf(StandAloneDependency::class);
});
