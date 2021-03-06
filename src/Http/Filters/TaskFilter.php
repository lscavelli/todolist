<?php

namespace Lfgscavelli\Todolist\Http\Filters;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilter extends QueryFilter
{

    public function user() {
        if (!auth()->user()->isAdmin()) {
            $id = auth()->user()->id;
            $this->builder
                ->where('user_id',$id)
                ->orWhereHas('users', function ($q) use ($id) {
                    $q->where('user_id', $id);})
                ->orWhereHas('groups.users', function ($q) use ($id) {
                    $q->where('user_id', $id);
                });
        };
    }

    public function assignToMe() {
        $id = auth()->user()->id;
        $this->builder
            ->where(function ($query) use ($id) {
                // creati da me e non assegnati a user o gruppi
                // --------------------------------------------
                $query->where(function ($q) use ($id) {
                    $q->where('user_id',$id)->whereDoesntHave('users')->whereDoesntHave('groups');});
                // assegnati a me
                // --------------------------------------------
                $query->orWhereHas('users', function ($q) use ($id) {
                    $q->where('user_id', $id);
                });
                // assegnati ai gruppi a cui appartengo
                // --------------------------------------------
                $query->orWhereHas('groups.users', function ($q) use ($id) {
                    $q->where('user_id', $id);
                });
            });
    }

    public function assignToOther() {
        $id = auth()->user()->id;
        $this->builder
            ->where(function ($query) use ($id) {
                // creati da altri e non assegnati a user o gruppi
                // --------------------------------------------
                $query->where(function ($q) use ($id) {
                    $q->where('user_id',"!=",$id)->whereDoesntHave('users')->whereDoesntHave('groups');});
                // assegnati ad altri user
                // --------------------------------------------
                $query->orWhereHas('users', function ($q) use ($id) {
                    $q->where('user_id', "!=", $id);
                });
                // assegnati ai gruppi a cui non appartengo
                // --------------------------------------------
                $query->orWhereHas('groups.users', function ($q) use ($id) {
                    $q->where('user_id', "!=", $id);
                });
            });
    }

    public function miei() {
        $this->builder->where('user_id',auth()->user()->id);
    }
    public function open() {
        $this->builder
            ->where(function ($query) {
                $query->where('status_id',1)->orWhere('status_id','>' ,3);
            });
    }
    public function closed() {
        $this->builder
            ->where(function ($query) {
                $query->where('status_id',2);
            });
    }
    public function sospeso() {
        $this->builder->where('tasks.status_id',3);
    }

    public function categories() {

        if (request()->has('tag')) {
            $tags = ['tags'=>['tag'=>request()->tag]];
            $this->builder
                ->where(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        $query->whereHas('tags', function ($q) use ($tag) {
                            $q->where('tag_id', '=', $tag['tag']);
                        });
                    }
                });
        }

        if (request()->has('category')) {
            $categories = ['categories'=>['category'=>request()->category]];
            $this->builder
                ->where(function ($query) use ($categories) {
                    foreach ($categories as $category) {
                        $query->whereHas('categories', function ($q) use ($category) {
                            $q->where('category_id', $category['category']);
                        });
                    }
                });
        }

    }
}
