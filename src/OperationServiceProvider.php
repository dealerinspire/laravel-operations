<?php

namespace DealerInspire\Operations;

use DealerInspire\Operations\Console\MigrationCreator;
use Illuminate\Support\ServiceProvider;
use DealerInspire\Operations\Console\QueueCommand;
use DealerInspire\Operations\Console\MakeOperationCommand;
use DealerInspire\Operations\Console\MakeOperationMigrationCommand;

class OperationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/operations.php' => config_path('operations.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                QueueCommand::class,
                MakeOperationCommand::class,
                MakeOperationMigrationCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->alias(Operator::class, 'operator');

        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });
    }
}
