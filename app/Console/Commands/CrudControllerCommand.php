<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrudControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new CRUD controller.';

    protected function getStub()
    {
        return base_path('stubs/crud.controller.stub');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $template = str_replace(
            '{{model}}',
            $name,
            file_get_contents($this->getStub())
        );

        $path = "app/Http/Controllers/Api/{$name}Controller.php";

        $file = base_path($path);

        file_put_contents($file, $template);

        $this->info("Controller [$path] created successfully.");
    }
}
