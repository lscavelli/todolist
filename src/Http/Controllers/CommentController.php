<?php

namespace Lfgscavelli\Todolist\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\listGenerates;
use App\Models\Content\Comment;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Lfgscavelli\Todolist\Models\Task;

class CommentController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware(['web', 'auth']);
        $this->rp = $rp->setModel('App\Models\Content\Comment')->setSearchFields(['name','content']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            'name' => 'required|min:10',
            'content' => 'required|min:10'] );
    }

    /**
     * Mostra il form per la creazione di un nuovo commento
     * @return \Illuminate\Contracts\View\View
     */
    public function create($task_id)   {
        $task = $this->rp->getModel(Task::class)->findOrFail($task_id);
        $comment = new Comment();
        return view('todolist::editComment')->with(compact('comment','task'));
    }

    /**
     * Salva il commento nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $task = $this->rp->getModel(Task::class)->findOrFail($request->get('id'));
        $data = $request->all();
        if (empty($data['name'])) $data['name'] = Str::limit($data['content'],50);
        $data['author_ip'] = $request->ip();
        $data['user_id'] = auth()->user()->id;
        $this->validator($data)->validate();
        $task->comments()->create($data);
        return redirect('/admin/tasks',[$request->get('id')])->withSuccess('Commento creato correttamente.');
    }


}
