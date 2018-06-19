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


}