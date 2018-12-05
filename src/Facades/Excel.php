<?php

namespace TechOne\Excel\Facades;

use TechOne\Support\Facade;

class Excel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TechOne\Excel\Excel::init();
    }
}
