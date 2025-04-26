<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:views {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea vistas blade básicas: index, create, edit, show';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $viewPath = resource_path('views/' . $this->argument('path'));

        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
        }

        foreach (['index', 'create', 'edit', 'show'] as $view) {
            $file = $viewPath . '/' . $view . '.blade.php';

            if (!file_exists($file)) {
                file_put_contents($file, "<!-- $view view -->");
                $this->info("Created: $file");
            } else {
                $this->warn("Already exists: $file");
            }
        }

        return 0;
    }

}
