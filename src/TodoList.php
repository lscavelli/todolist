<?php

namespace Lfgscavelli\Todolist;

use Lfgscavelli\Todolist\Models\User;
use App\Repositories\RepositoryInterface;

class TodoList
{
    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->rp = $rp->setModel('Lfgscavelli\Todolist\Models\Task');
    }

    public function hello()
    {
        return "hello package TofoList";
    }

    public function tasksOfUser() {
        $id = auth()->user()->id;
        $user = $this->rp->setModel(User::class)->find($id);
        $tasks = $user->tasks()->orderBy('position','ASC')->paginate(5);
        return view('todolist::listForDash')->with(compact('tasks'));
    }

    public function categories() {
        $vocabularies = $this->listVocabularies('Lfgscavelli\Todolist\Models\Task');
        return view('todolist::categories')->with(compact('vocabularies'));
    }

    public function calendar() {
        return view('todolist::calendar')->with(compact(''));
    }

    public function listVocabularies($model) {
        if (is_string($model)) {
            $class = $model;
        } elseif($model instanceof EloquentModel) {
            $class = get_class($model);
        } else {
            return;
        }
        $service = $this->rp->setModel('Lfgscavelli\Todolist\Models\Service')->where('class',$class)->firstOrFail();
        return $service->vocabularies;
    }

    public function setPermissions($name, $slug, $description=null) {
        return $this->rp->setModel('App\Models\Permission')
            ->create([
                'name'          => $name,
                'slug'          => $slug,
                'description'   => $description
            ]);
    }

}