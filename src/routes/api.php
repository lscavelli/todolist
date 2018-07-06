<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/
Route::group([
    'namespace' => 'Lfgscavelli\Todolist\Http\Controllers\Api',
    'middleware' => ['cors'],
    'prefix' => 'api',
],
    function () {

        Route::get('tasks/bydate', 'TaskController@byDate');
        Route::apiResource('tasks', 'TaskController');


    }
);