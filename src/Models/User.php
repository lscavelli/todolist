<?php

namespace Lfgscavelli\Todolist\Models;

use App\Models\User as UserApp;

class User extends UserApp
{
    /**
     * m-m - List of tasks for User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks() {
        return $this->belongsToMany('Lfgscavelli\Todolist\Models\Task','tasks_users');
    }
}
