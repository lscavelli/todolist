<?php

namespace Lfgscavelli\Todolist\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table = 'statuses';

    protected $fillable = [
        'name', 'color', 'icon'
    ];

    /**
     * restituisce l'elenco dei task che hanno lo stato selezionato
     * @return mixed
     */
    public function tasks() {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

}
