<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/
Route::group([
    'namespace' => 'Lfgscavelli\Todolist\Http\Controllers\Api',
    'middleware' => ['cors','web','auth'],
    'prefix' => 'admin/api/tasks',
],
    function () {

        Route::get('bydate', 'TaskController@byDate');
        Route::get('changestate/{task_id}/{status_id}', 'TaskController@changeState');
        Route::get('categories', 'TaskController@listCategories');
        Route::apiResource('/', 'TaskController');
        Route::get('order', 'TaskController@setOrder');
        Route::get('delete/{task_id}', 'TaskController@destroy');
    }
);