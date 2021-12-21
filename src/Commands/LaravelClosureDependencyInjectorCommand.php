<?php

namespace Elliottlawson\LaravelClosureDependencyInjector\Commands;

use Illuminate\Console\Command;

class LaravelClosureDependencyInjectorCommand extends Command
{
    public $signature = 'laravel-closure-dependency-injector';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
