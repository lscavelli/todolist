<?php
Route::get('/hello', function () {
    return Todolist::hello();
});

Route::get('/testhello', function () {
    return app('todo-list')->hello();
});

/*
Route::group(['namespace' => 'Lfgscavelli\Todolist\Controllers'], function () {
    Route::get('/admin/tasks', 'TaskController@list');
});
*/