<?php

namespace Naowas\CrudGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class CrudGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'CrudGenerator';
    }
}
