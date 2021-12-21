<?php

namespace Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers;

class AbstractDependency
{
    public function run(): string
    {
        return 'success';
    }
}
