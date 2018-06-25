<?php

namespace Lfgscavelli\Todolist\Models;

use App\Models\Content\Service as ServiceApp;

class Service extends ServiceApp
{
    /**
     * m-m
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vocabularies() {
        return $this->belongsToMany('Lfgscavelli\Todolist\Models\Vocabulary','vocabularies_services');
    }
}
