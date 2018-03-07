<?php

namespace Lfgscavelli\Todolist\facades;

use Illuminate\Support\Facades\Facade;

class TodoListFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'todo-list';
    }
}