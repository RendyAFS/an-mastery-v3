<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example:
     * php artisan make:action User/CreateUser
     */
    protected $signature = 'make:action {name : The action name}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new Action class';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = trim($this->argument('name'));

        // Ubah menjadi array folder
        $segments = explode('/', str_replace('\\', '/', $name));

        // Ambil nama class terakhir
        $className = Str::studly(array_pop($segments));

        // Tambahkan suffix Action jika belum ada
        if (!Str::endsWith($className, 'Action')) {
            $className .= 'Action';
        }

        // Tentukan namespace
        $namespace = 'App\\Actions';

        if (!empty($segments)) {
            $namespace .= '\\' . collect($segments)
                ->map(fn($segment) => Str::studly($segment))
                ->implode('\\');
        }

        // Tentukan folder tujuan
        $directory = app_path(
            'Actions/' .
                collect($segments)
                ->map(fn($segment) => Str::studly($segment))
                ->implode('/')
        );

        // Buat folder jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $className . '.php';

        // Cek jika file sudah ada
        if (File::exists($path)) {
            $this->error("Action already exists: {$path}");

            return self::FAILURE;
        }

        // Isi file
        $stub = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    /**
     * Execute the action.
     */
    public function execute(array \$data)
    {
        //
    }
}

PHP;

        File::put($path, $stub);

        $this->info("Action created successfully:");
        $this->line($path);

        return self::SUCCESS;
    }
}
