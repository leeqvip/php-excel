<?php

namespace TechOne\Excel\Facades;

use think\Facade;
use think\Container;
use TechOne\Excel\Writer;

class Excel extends Facade
{
    protected static function getFacadeClass()
    {
        if (!Container::getInstance()->has('excel')) {
            Container::getInstance()->bindTo('excel', function () {
                return new \TechOne\Excel\Excel(
                    app(Writer::class)
                );
            });
        }

        return 'excel';
    }
}
