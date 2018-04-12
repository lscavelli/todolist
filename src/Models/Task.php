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

    /**
     * m-m
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()    {
        return $this->belongsToMany('App\Models\Group','tasks_groups');
    }

    /**
     * m-m
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\Models\User','tasks_users');
    }
}
