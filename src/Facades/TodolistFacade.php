<?php

namespace Lfgscavelli\Todolist\Facades;

use Illuminate\Support\Facades\Facade;

class TodolistFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'todo-list';
    }
}