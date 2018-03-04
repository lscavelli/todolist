<?php


Route::group(['namespace' => 'Lfgscavelli\Todolist\Controllers'], function () {
    Route::get('/admin/tasks', 'TaskController@list');
});