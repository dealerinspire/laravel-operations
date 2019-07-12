<?php

namespace DealerInspire\Operations\Tests;

use Carbon\Carbon;
use DealerInspire\Operations\OperationJob;
use DealerInspire\Operations\Tests\Operations\AnotherOperation;
use DealerInspire\Operations\Tests\Operations\ExampleOperation;
use DealerInspire\Operations\Tests\Operations\StoppingOperation;
use DealerInspire\Operations\Tests\Operations\CancelingOperation;
use DealerInspire\Operations\Tests\Operations\DependantOperation;

class OperationJobTest extends TestCase
{
    /** @test */
    public function it_can_hold_an_operation()
    {
        $operation = new ExampleOperation();

        $operationJob = new OperationJob($operation);

        $this->assertSame($operation, $operationJob->getOperation());
    }

    /** @test */
    public function handle_successfully_runs_operation()
    {
        $operation = new ExampleOperation();
        $operation->should_run_at = Carbon::now();
        $operation->save();

        $operationJob = new OperationJob($operation);

        $this->assertFalse($operation->hasRun());
        $this->assertNull($operation->finished_run_at);

        $operationJob->handle();

        $operation->refresh();
        $this->assertTrue($operation->hasRun());
        $this->assertNotNull($operation->finished_run_at);
    }

    /** @test */
    public function handle_successfully_binds_dependencies()
    {
        $operation = new DependantOperation();
        $operation->should_run_at = Carbon::now();

        $operationJob = new OperationJob($operation);

        $operationJob->handle();

        $this->assertNotNull($operation->fresh()->finished_run_at);
    }

    /** @test */
    public function it_stops_the_job_when_operation_stopped_exception_is_thrown()
    {
        $operation = new StoppingOperation();
        $operation->should_run_at = Carbon::now()->subMinutes(5);
        $operation->started_run_at = Carbon::now()->subMinute();
        $operation->save();

        $operationJob = new OperationJob($operation);

        $operationJob->handle();

        $operation->refresh();
        $this->assertNull($operation->started_run_at);
        $this->assertNull($operation->finished_run_at);
        $this->assertNull($operation->deleted_at);
    }

    /** @test */
    public function it_cancels_the_job_when_operation_canceled_exception_is_thrown()
    {
        $operation = new CancelingOperation();
        $operation->should_run_at = Carbon::now()->subMinutes(5);
        $operation->started_run_at = Carbon::now()->subMinute();
        $operation->save();

        $operationJob = new OperationJob($operation);

        $operationJob->handle();

        $operation->refresh();
        $this->assertEquals(Carbon::now()->subMinute(), $operation->started_run_at);
        $this->assertNull($operation->finished_run_at);
        $this->assertNotNull($operation->deleted_at);
    }

    /** @test */
    public function it_has_the_proper_display_name()
    {
        $exampleOperation = new ExampleOperation();
        $exampleOperationJob = new OperationJob($exampleOperation);
        $this->assertSame(ExampleOperation::class, $exampleOperationJob->displayName());

        $anotherOperation = new AnotherOperation();
        $anotherOperationJob = new OperationJob($anotherOperation);
        $this->assertSame(AnotherOperation::class, $anotherOperationJob->displayName());
    }

    /** @test */
    public function it_has_the_proper_tags()
    {
        $exampleOperation = new ExampleOperation();
        $exampleOperation->id = 123;
        $exampleOperationJob = new OperationJob($exampleOperation);
        $expectedExampleTags = [
            'operation',
            ExampleOperation::class.':123',
        ];
        $this->assertSame($expectedExampleTags, $exampleOperationJob->tags());

        $anotherOperation = new AnotherOperation();
        $anotherOperation->id = 456;
        $anotherOperationJob = new OperationJob($anotherOperation);
        $expectedAnotherTags = [
            'operation',
            AnotherOperation::class.':456',
        ];
        $this->assertSame($expectedAnotherTags, $anotherOperationJob->tags());
    }
}
