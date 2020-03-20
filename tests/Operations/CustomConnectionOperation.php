<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;

class CustomConnectionOperation extends Operation
{
    public $queueConnection = 'custom';

    public function run()
    {
        //
    }
}
