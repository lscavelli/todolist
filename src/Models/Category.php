<?php

namespace Lfgscavelli\Todolist\Models;

use App\Models\Content\Category as CategoryApp;

class Category extends CategoryApp
{
    /**
     * List of tasks for categories
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tasks() {
        return $this->morphedByMany('Lfgscavelli\Todolist\Models\Task', 'categorized')->withCount(['tasks_users'=> function ($query) {
            $query->where('user_id',auth()->guard()->id);
        }]);
    }

}
