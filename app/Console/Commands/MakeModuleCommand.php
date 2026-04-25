<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature   = 'make:module {name} {--resource : Generate resource controller instead of basic controller}';
    protected $description = 'Generate module (Controller, Repository, Request)';

    public function handle()
    {
        $name = $this->argument('name');
        $isResource = $this->option('resource');

        $studly = Str::studly($name);

        $this->info("🚀 Generating module: {$studly}");

        // Controller
        $controllerParams = [
            'name' => "{$studly}Controller",
        ];

        if ($isResource) {
            $controllerParams['--resource'] = true;
        }

        $this->call('make:controller', $controllerParams);

        // Repository
        $this->call('make:class', [
            'name' => "Repositories/{$studly}Repository",
        ]);

        // Request
        $this->call('make:request', [
            'name' => "{$studly}/Save{$studly}Request",
        ]);

        $this->info("✅ Module {$studly} generated successfully!");
    }
}
