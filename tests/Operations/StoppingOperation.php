<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;
use DealerInspire\Operations\Exceptions\OperationStoppedException;

class StoppingOperation extends Operation
{
    public function run()
    {
        throw new OperationStoppedException();
    }
}
