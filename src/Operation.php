<?php

namespace DealerInspire\Operations;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DealerInspire\Operations\Exceptions\OperationStoppedException;
use DealerInspire\Operations\Exceptions\OperationCanceledException;

abstract class Operation extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'started_run_at',
        'finished_run_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'should_run_at',
        'started_run_at',
        'finished_run_at',
    ];

    /**
     * @throws OperationStoppedException
     */
    public function stop()
    {
        throw new OperationStoppedException();
    }

    /**
     * @throws OperationCanceledException
     */
    public function cancel()
    {
        throw new OperationCanceledException();
    }

    public static function schedule($shouldRunAt, $attributes = [])
    {
        static::create(array_merge(['should_run_at' => $shouldRunAt], $attributes));
    }

    public static function dispatch($attributes = [])
    {
        static::schedule(Carbon::now(), $attributes);
    }
}
