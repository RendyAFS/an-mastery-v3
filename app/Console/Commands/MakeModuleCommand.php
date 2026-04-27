<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name} {--resource : Generate resource controller} {--simple : Generate simple CRUD (modal based)}';
    protected $description = 'Generate module (Controller, Repository, Request)';

    public function handle()
    {
        $name = $this->argument('name');
        $isResource = $this->option('resource');
        $isSimple = $this->option('simple');
        $kebab = Str::kebab($name);

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

        $baseViewPath = resource_path("views/{$kebab}");
        $baseJsPath   = resource_path("js/pages/{$kebab}");

        // common files
        $this->createFile("{$baseViewPath}/index.blade.php");
        $this->createFile("{$baseViewPath}/form.blade.php");
        $this->createFile("{$baseJsPath}/list.js");

        if ($isSimple) {
            $this->createFile("{$baseViewPath}/modal.blade.php");
        } else {
            $this->createFile("{$baseViewPath}/create.blade.php");
            $this->createFile("{$baseViewPath}/edit.blade.php");
            $this->createFile("{$baseJsPath}/form.js");
        }

        $this->info("✅ Module {$studly} generated successfully!");
    }

    private function createFile($path, $content = '')
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        if (!file_exists($path)) {
            file_put_contents($path, $content);
            $this->info("📄 Created: {$path}");
        } else {
            $this->warn("⚠️ Already exists: {$path}");
        }
    }
}
