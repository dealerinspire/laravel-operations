<?php

namespace DealerInspire\Operations\Tests\Operations;

use DealerInspire\Operations\Operation;

class TaggedOperation extends Operation
{
    public function run()
    {
        //
    }

    public function tags()
    {
        return [
            'custom-tags',
            'foobar',
            'tagged',
        ];
    }
}
