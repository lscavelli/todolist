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
        Route::get('tasks/list/{type?}', 'TaskController@index');
        Route::resource('tasks','TaskController');
        Route::get('tasks/categorization/{task_id}', 'TaskController@Categorization');
        Route::get('tasks/assignUsers/{task_id}', 'TaskController@assignUsers');
        Route::get('tasks/assignGroups/{task_id}', 'TaskController@assignGroups');
        Route::get('tasks/{task_id}/addGroup/{group_id}', 'TaskController@addGroup');
        Route::get('tasks/{task_id}/removeGroup/{group_id}', 'TaskController@delGroup');
        Route::get('tasks/{task_id}/addUser/{user_id}', 'TaskController@addUser');
        Route::get('tasks/{task_id}/removeUser/{user_id}', 'TaskController@delUser');
        Route::get('tasks/closed/{task_id}', 'TaskController@closed');
        Route::get('tasks/open/{task_id}', 'TaskController@open');
        Route::get('tasks/files/{task_id}', 'TaskController@listFiles');

        Route::post('tasks/categories/{task_id}', 'TaskController@saveCategories');
    }
);
