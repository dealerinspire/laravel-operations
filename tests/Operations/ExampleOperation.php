<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;

class ExampleOperation extends Operation
{
    protected $hasRun = false;

    public function run()
    {
        $this->hasRun = true;
    }

    public function hasRun(): bool
    {
        return $this->hasRun;
    }
}
