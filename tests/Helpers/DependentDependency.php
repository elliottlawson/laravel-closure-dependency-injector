<?php

namespace Elliottlawson\LaravelClosureDependencyInjector\Tests\Helpers;

class DependentDependency
{
    public function __construct(
        public StandAloneDependency $dependency,
    ){}
}