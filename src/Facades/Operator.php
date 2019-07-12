<?php

namespace DealerInspire\Operations\Facades;

use Illuminate\Support\Facades\Facade;

final class Operator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'operator';
    }
}
