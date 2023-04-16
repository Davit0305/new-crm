<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service class to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $filesystem = new Filesystem();

        // Path to the service class stub file
        $stubPath = $this->getStubPath();

        if (! $filesystem->exists($stubPath)) {
            $this->error('Service stub file not found!');
            return;
        }

        $stub = $filesystem->get($stubPath);

        // Generate the name and namespace of the service class
        $class = Str::studly($name).'Service';
        $namespace = 'App\Services';

        // Replace placeholders in the stub file with actual class and namespace
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$class, $namespace],
            $stub
        );

        // Write the service class file to disk
        $path = app_path('Services/'.$class.'.php');

        $filesystem->put($path, $content);

        $this->info('Service created successfully.');
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service class to create.'],
        ];
    }

    /**
     * Get the path to the stub file for the service class.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__.'/stubs';
    }
}
