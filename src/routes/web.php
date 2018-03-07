<?php
/*
Route::get('/hello', function () {
    return TodoList::hello();
});

Route::get('/testhello', function () {
    return app('todo-list')->hello();
});
*/

Route::group([
        'prefix'=>'admin',
        'middleware' => ['web', 'auth'],
        'namespace' => 'Lfgscavelli\Todolist\Http\Controllers'
    ],
    function () {
        Route::resource('/tasks', 'TaskController');
    }
);
