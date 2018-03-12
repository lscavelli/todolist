<?php

namespace Lfgscavelli\Todolist\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = array(
        'name', 'description', 'date', 'type', 'priority', 'status_id', 'done'
    );

    protected $dates = [
        'date'
    ];

    public function groups()    {
        return $this->belongsToMany('App\Models\Group','tasks_groups');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User','tasks_users');
    }
}
