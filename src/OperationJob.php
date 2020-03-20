<?php

namespace DealerInspire\Operations;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DealerInspire\Operations\Exceptions\OperationStoppedException;
use DealerInspire\Operations\Exceptions\OperationCanceledException;

class OperationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function handle(): void
    {
        try {
            App::call([$this->operation, 'run']);
        } catch (OperationStoppedException $e) {
            $this->operation->started_run_at = null;
            $this->operation->save();
            return;
        } catch (OperationCanceledException $e) {
            $this->operation->delete();
            return;
        }

        $this->operation->finished_run_at = Carbon::now();
        $this->operation->save();
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function displayName()
    {
        return get_class($this->operation);
    }

    public function tags()
    {
        return $this->operation->tags();
    }
}
