<?php

namespace Lfgscavelli\Todolist\routes;

class TaskRoutes
{
    public static function routes()
    {
        Route::get('/admin/tasks', '\Lfgscavelli\Todolist\Controllers\TaskController@list');
        Route::get('/admin/tasks/categories/{task_id}', '\Lfgscavelli\Todolist\Controllers\TaskController@saveCategories');

    }
}