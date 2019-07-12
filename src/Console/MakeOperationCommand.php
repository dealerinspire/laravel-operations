<?php

namespace DealerInspire\Operations\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

final class MakeOperationCommand extends GeneratorCommand
{
    protected $name = 'make:operation';

    protected $description = 'Create a new operation';

    protected $type = 'Operation';

    public function handle()
    {
        parent::handle();

        if ($this->option('migration')) {
            $this->createMigration();
        }
    }

    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        $this->call('make:operation-migration', [
            'name' => $this->argument('name'),
        ]);
    }

    protected function getStub()
    {
        return __DIR__.'/../../stubs/operation.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Operations';
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyOperation', $this->argument('name'), $stub);
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the operation'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the operation'],
        ];
    }
}
