<?php

namespace DealerInspire\Operations\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use DealerInspire\Operations\OperationServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use DealerInspire\Operations\Tests\Operations\AnotherOperation;
use DealerInspire\Operations\Tests\Operations\ExampleOperation;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database');

        Carbon::setTestNow('2019-07-04 12:00:00');
        Queue::fake();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('operations.operations', [
            ExampleOperation::class,
            AnotherOperation::class,
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            OperationServiceProvider::class,
        ];
    }
}
