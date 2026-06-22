<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = '
        make:module
        {name}
        {--resource : Generate resource controller}
        {--simple : Generate simple CRUD (modal based)}
        {--default : Generate default listing module}
    ';

    protected $description = 'Generate module structure';

    public function handle(): int
    {
        $name       = $this->argument('name');
        $isResource = $this->option('resource');
        $isSimple   = $this->option('simple');
        $isDefault  = $this->option('default');

        $studly = Str::studly($name);
        $kebab  = Str::kebab($name);

        $this->newLine();
        $this->components->info("🚀 Generating module: {$studly}");
        $this->line(str_repeat('─', 50));

        /*
        |--------------------------------------------------------------------------
        | Generate Backend
        |--------------------------------------------------------------------------
        */

        $this->components->twoColumnDetail('Module', $studly);
        $this->components->twoColumnDetail('Controller Type', $isResource ? 'Resource' : 'Basic');
        $this->components->twoColumnDetail('CRUD Type', $isDefault ? 'Default' : ($isSimple ? 'Simple Modal' : 'Full Page'));

        $this->newLine();

        $this->components->task('Generating Controller', function () use ($studly, $isResource, $isDefault) {

            $params = [
                'name' => "{$studly}Controller",
            ];

            if ($isResource && ! $isDefault) {
                $params['--resource'] = true;
            }

            $this->call('make:controller', $params);

            return true;
        });

        $this->components->task('Generating Repository', function () use ($studly) {

            $this->call('make:class', [
                'name' => "Repositories/{$studly}Repository",
            ]);

            return true;
        });

        $this->components->task('Generating Request', function () use ($studly) {

            $this->call('make:request', [
                'name' => "{$studly}/Save{$studly}Request",
            ]);

            return true;
        });

        $this->components->task('Generating Resource', function () use ($studly) {

            $this->call('make:resource', [
                'name' => "{$studly}Resource",
            ]);

            return true;
        });

        /*
        |--------------------------------------------------------------------------
        | Generate Views & JS
        |--------------------------------------------------------------------------
        */

        $baseViewPath = resource_path("views/{$kebab}");
        $baseJsPath   = resource_path("js/pages/{$kebab}");

        $files = [];

        if ($isDefault) {
            $files = [
                "{$baseViewPath}/index.blade.php",
                "{$baseJsPath}/index.js",
            ];
        } else {

            $files = [
                "{$baseViewPath}/index.blade.php",
                "{$baseViewPath}/form.blade.php",
                "{$baseJsPath}/list.js",
            ];

            if ($isSimple) {
                $files[] = "{$baseViewPath}/modal.blade.php";
            } else {
                $files[] = "{$baseViewPath}/create.blade.php";
                $files[] = "{$baseViewPath}/edit.blade.php";
                $files[] = "{$baseJsPath}/form.js";
            }
        }

        $this->newLine();
        $this->components->info('📁 Generating View & JS Files');

        foreach ($files as $file) {
            $this->components->task($file, function () use ($file) {
                $this->createFile($file);
                return true;
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Finish
        |--------------------------------------------------------------------------
        */

        $this->newLine();
        $this->line(str_repeat('─', 50));

        $this->components->info("✅ Module {$studly} generated successfully!");

        $this->table(
            ['Type', 'Path'],
            [
                ['Views', "resources/views/{$kebab}"],
                ['JS', "resources/js/pages/{$kebab}"],
                ['Controller', "app/Http/Controllers/{$studly}Controller.php"],
                ['Repository', "app/Repositories/{$studly}Repository.php"],
            ]
        );

        $this->newLine();
        return self::SUCCESS;
    }

    private function createFile(string $path, string $content = ''): void
    {
        File::ensureDirectoryExists(dirname($path));

        if (!File::exists($path)) {
            File::put($path, $content);
        }
    }
}
