<?php

namespace Lfgscavelli\Todolist\facades;

use Illuminate\Support\Facades\Facade;

class TodolistFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'todo-list';
    }
}