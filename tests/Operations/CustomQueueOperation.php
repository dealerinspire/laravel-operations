<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;

class CustomQueueOperation extends Operation
{
    public $queue = 'custom';

    public function run()
    {
        //
    }
}
