<?php

namespace Lfgscavelli\Todolist\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Content\File;

class Task extends Model
{
    use Filterable;

    protected $table = 'tasks';

    protected $fillable = array(
        'name', 'description', 'date', 'type', 'priority',
        'status_id', 'done', 'position', 'user_id'
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
    public function categories()
    {
        return $this->morphToMany('App\Models\Content\Category', 'categorized')->withPivot('vocabulary_id');
    }

    /**
     * restituisce l'autore del task
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * elenco dei file di un task
     * @return mixed
     */
    public function files() {
        return $this->belongsToMany(File::class);
    }

    /**
     * restituisce i commenti associati al Task
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments() {
        return $this->morphMany('App\Models\Content\Comment','commentable');
    }

}
