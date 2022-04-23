<?php

namespace Elliottlawson\LaravelClosureDependencyInjector;

use Closure;
use Illuminate\Support\Arr;
use ReflectionFunction;
use ReflectionParameter;

class DependencyEngine
{
    protected Closure $closure;

    protected array $arguments;

    public static function handle(?Closure $closure = null, bool $autoRun = true): static
    {
        $self = new static();

        if (is_null($closure)) {
            return $self;
        }

        $self->arguments = $self::resolveArguments($closure);
        $self->closure = $closure;

        if ($autoRun) {
            $closure(...$self->arguments);
        }

        return $self;
    }

    public function runClosure(): void
    {
        $closure = $this->closure;

        $closure(...$this->arguments);
    }

    public static function resolveArguments(Closure $closure, ?string $instance = null, mixed $parameter = null): array
    {
        $object = new ReflectionFunction($closure);

        return collect($object->getParameters())
            ->map(fn ($argument) => static::resolveArgument($argument, $instance, $parameter))
            ->toArray();
    }

    public static function resolveArgument(ReflectionParameter $argument, ?string $instance = null, mixed $parameter = null): object
    {
        throw_if(static::notTypeHinted($argument), 'Arguments must be type hinted');

        throw_if(static::isNotInstantiable($argument), 'Argument is not instantiable. Variables should be passed via use');

        if ($instance) {
            static::verifyArgumentIsInstanceOf($argument, $instance);
        }

        return static::instantiate($argument, $parameter);
    }

    protected static function notTypeHinted(ReflectionParameter $argument): bool
    {
        return $argument->hasType() === false;
    }

    protected static function isNotInstantiable(ReflectionParameter $argument): bool
    {
        $class = self::resolveClass($argument);

        return class_exists($class) === false;
    }

    protected static function verifyArgumentIsInstanceOf(ReflectionParameter $argument, string $instance): void
    {
        $type = self::resolveClass($argument);

        throw_if(is_a($type, $instance, true) === false, "Arguments are required to be instances of {$instance}");
    }

    protected static function instantiate(ReflectionParameter $argument, mixed $parameter = null): object
    {
        $class = self::resolveClass($argument);

        if ($parameter) {
            return new $class(...Arr::wrap($parameter));
        }

        return new $class();
    }

    protected static function resolveClass(ReflectionParameter $argument): string
    {
        return $argument->getType()?->getName();
    }
}
