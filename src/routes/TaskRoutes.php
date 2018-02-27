<?php

class TaskRoutes
{
    public static function routes()
    {
        Route::get('/admin/tasks', '\Lfgscavelli\Todolist\Controllers\TaskController@index');
    }
}