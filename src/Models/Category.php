<?php

namespace Lfgscavelli\Todolist\Models;

use App\Models\Content\Category as CategoryApp;

class Category extends CategoryApp
{
    /**
     * List of tasks for categories
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tasksClosed() {
        //return $this->morphedByMany('Lfgscavelli\Todolist\Models\Task', 'categorized')->where('status_id',2)->whereHas('users', function ($query) {
        //    $query->where('user_id',auth()->user()->id);
        //});
        return $this->morphedByMany('Lfgscavelli\Todolist\Models\Task', 'categorized')->where('status_id',2);
    }

    public function tasks() {
        return $this->morphedByMany('Lfgscavelli\Todolist\Models\Task', 'categorized');
    }

}
