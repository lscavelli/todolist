<?php

namespace Lfgscavelli\Todolist\routes;

class TaskRoutes
{
    public static function routes()
    {
        Route::get('/admin/tasks', '\Lfgscavelli\Todolist\Controllers\TaskController@list');
    }
}