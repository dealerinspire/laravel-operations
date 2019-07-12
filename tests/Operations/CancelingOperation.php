<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;
use DealerInspire\Operations\Exceptions\OperationCanceledException;

class CancelingOperation extends Operation
{
    public function run()
    {
        throw new OperationCanceledException();
    }
}
