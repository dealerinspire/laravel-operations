<?php

namespace DealerInspire\Operations\Tests;

use Carbon\Carbon;
use DealerInspire\Operations\Tests\Operations\ColumnOperation;
use DealerInspire\Operations\Tests\Operations\ExampleOperation;
use DealerInspire\Operations\Exceptions\OperationStoppedException;
use DealerInspire\Operations\Exceptions\OperationCanceledException;

class OperationTest extends TestCase
{
    /** @test */
    public function started_run_at_is_the_only_fillable_default_field()
    {
        ExampleOperation::create([
            'id' => 17,
            'should_run_at' => Carbon::now()->addHours(5),
            'started_run_at' => Carbon::now()->subHours(2),
            'finished_run_at' => Carbon::now()->addHours(3),
            'created_at' => Carbon::now()->subWeek(),
            'updated_at' => Carbon::now()->addWeek(),
            'deleted_at' => Carbon::now(),
        ]);

        $operation = ExampleOperation::withTrashed()->first();
        $this->assertNotEquals(17, $operation->id, 'ID should not be fillable');
        $this->assertEquals(Carbon::now()->addHours(5), $operation->should_run_at, 'should_run_at should be fillable');
        $this->assertNull($operation->started_run_at, 'started_run_at should not be fillable');
        $this->assertNull($operation->finished_run_at, 'finished_run_at should not be fillable');
        $this->assertEquals(Carbon::now(), $operation->created_at, 'created_at should not be fillable');
        $this->assertEquals(Carbon::now(), $operation->updated_at, 'updated_at should not be fillable');
        $this->assertNull($operation->deleted_at, 'deleted_at should not be fillable');
    }

    /** @test */
    public function it_can_stop_itself()
    {
        $this->expectException(OperationStoppedException::class);

        $operation = new ExampleOperation();

        $operation->stop();
    }

    /** @test */
    public function it_can_cancel_itself()
    {
        $this->expectException(OperationCanceledException::class);

        $operation = new ExampleOperation();

        $operation->cancel();
    }

    /** @test */
    public function it_can_schedule_itself()
    {
        ExampleOperation::schedule(Carbon::now()->addDay());

        $operation = ExampleOperation::first();
        $this->assertEquals(Carbon::now()->addDay(), $operation->should_run_at);
    }

    /** @test */
    public function it_can_schedule_itself_with_custom_columns()
    {
        ColumnOperation::schedule(Carbon::now()->addDay(), [
            'value' => 2500,
            'message' => 'Custom Columns',
        ]);

        $operation = ColumnOperation::first();
        $this->assertEquals(Carbon::now()->addDay(), $operation->should_run_at);
        $this->assertEquals(2500, $operation->value);
        $this->assertEquals('Custom Columns', $operation->message);
    }

    /** @test */
    public function it_can_dispatch_itself_which_schedules_it_to_run_now()
    {
        ExampleOperation::dispatch();

        $operation = ExampleOperation::first();
        $this->assertEquals(Carbon::now(), $operation->should_run_at);
    }

    /** @test */
    public function it_can_dispatch_itself_to_run_now_with_custom_columns()
    {
        ColumnOperation::dispatch([
            'value' => 5000,
            'message' => 'Dispatching Operations',
        ]);

        $operation = ColumnOperation::first();
        $this->assertEquals(Carbon::now(), $operation->should_run_at);
        $this->assertEquals(5000, $operation->value);
        $this->assertEquals('Dispatching Operations', $operation->message);
    }
}
