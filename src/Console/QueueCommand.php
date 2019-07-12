<?php

namespace DealerInspire\Operations\Console;

use DealerInspire\Operations\Facades\Operator;
use Illuminate\Database\Console\Migrations\BaseCommand;

class QueueCommand extends BaseCommand
{
    protected $signature = 'operations:queue';

    protected $description = 'Queue any operations that are ready to be run';

    public function handle()
    {
        Operator::queue();
    }
}
