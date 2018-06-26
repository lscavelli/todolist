<?php

namespace Lfgscavelli\Todolist\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = array(
        'name', 'description', 'date', 'type', 'priority', 'status_id', 'done', 'position'
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

    /**
     * restituisce i tags assegnati al task
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags() {
        return $this->morphToMany('App\Models\Content\Tag', 'taggable');
    }

    /**
     * restituisce le categorie assegnate al task
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories() {
        return $this->morphToMany('App\Models\Content\Category', 'categorized')->withPivot('vocabulary_id');
    }

    public function scopeOpen($query) {
        return $query->where('status_id','!=' ,1)->where('status_id','!=' ,2);
    }

    public function scopeIsClosed($query) {
        return $query->where('status_id',1);
    }
}
