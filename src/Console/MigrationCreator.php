<?php

namespace DealerInspire\Operations\Console;

use Illuminate\Database\Migrations\MigrationCreator as IlluminateMigrationCreator;

class MigrationCreator extends IlluminateMigrationCreator
{
    protected function getStub($table, $create)
    {
        return $this->files->get(__DIR__.'/../../stubs/migration.stub');
    }
}
