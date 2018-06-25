<?php

namespace Lfgscavelli\Todolist\Models;

use App\Models\Content\Vocabulary as VocabularyApp;

class Vocabulary extends VocabularyApp
{
    /**
     * 1-m - rest. la lista delle categorie
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories() {
        return $this->hasMany('Lfgscavelli\Todolist\Models\Category','vocabulary_id');
    }

}
