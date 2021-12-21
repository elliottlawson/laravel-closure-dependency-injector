<?php

namespace Elliottlawson\LaravelClosureDependencyInjector;

use Closure;
use ReflectionFunction;
use ReflectionParameter;
use ReflectionType;
use RuntimeException;

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

    public static function resolveArgument(ReflectionParameter $argument, ?string $instance = null, mixed $parameter): mixed
    {
        if (static::notTypeHinted($argument)) {
            throw new RuntimeException('Arguments must be type hinted');
        }

        if (static::isNotInstantiable($argument)) {
            throw new RuntimeException('Argument is not instantiable. Variables should be passed via use');
        }

        if ($instance) {
            static::verifyArgumentIsInstanceOf($argument, $instance);
        }

        return static::instantiate($argument, $parameter);
    }

    protected static function notTypeHinted(ReflectionParameter $argument): bool
    {
        return ! $argument->hasType();
    }

    protected static function isNotInstantiable(ReflectionParameter $argument): bool
    {
        $class = self::resovleClass($argument);

        return ! class_exists($class);
    }

    protected static function verifyArgumentIsInstanceOf(ReflectionParameter $argument, string $instance): void
    {
        $type = self::resovleClass($argument);

        if (! is_a($type, $instance, true)) {
            throw new RuntimeException("Arguments are required to be instances of {$instance}");
        }
    }

    protected static function instantiate(ReflectionParameter $argument, mixed $parameter = null): object
    {
        $class = self::resovleClass($argument);

        if ($parameter) {
            return new $class($parameter);
        }

        return new $class();
    }

    protected static function resovleClass(ReflectionParameter $argument): string
    {
        /** @var ReflectionType $type */
        $type = $argument->getType();

        return $type->getName();
    }
}
