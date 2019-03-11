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
use Illuminate\Support\Str;

class CommentController extends Controller {

    private $rp;
    private $task;
    private $comment;

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
    public function store(Request $request)
    {
        $task = $this->rp->getModel(Task::class)->findOrFail($request->get('task_id'));
        $data = $request->all();
        if (empty($data['name'])) $data['name'] = Str::limit($data['content'],50);
        $data['author_ip'] = $request->ip();
        $data['user_id'] = auth()->user()->id;
        $this->validator($data)->validate();
        $task->comments()->create($data);
        return redirect('/admin/tasks/'.$request->get('task_id'))->withSuccess('Commento creato correttamente.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $task_id
     * @param $comment_id
     * @return mixed
     */
    public function edit($task_id, $comment_id)
    {
        $this->checkComment($comment_id);
        return view('todolist::editComment', ['comment'=>$this->comment,'task'=>$this->task]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->checkComment($id);
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('/admin/tasks/'.$this->task->id)->withSuccess('Commento modificato correttamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param $task_id
     * @param $comment_id
     * @return mixed
     */
    public function destroy($task_id,$comment_id)
    {
        $this->checkComment($comment_id);
        if ($this->rp->delete($comment_id)) {
            return redirect('/admin/tasks/'.$this->task->id)->withSuccess('Commento cancellato correttamente');
        }
    }

    private function checkComment($comment_id)
    {
        $this->comment = $this->rp->where('user_id',auth()->user()->id)->where('id',$comment_id)->firstOrFail();
        $this->task = $this->rp->getModel(Task::class)->findOrFail($this->comment->commentable_id);
        $this->rp->setModel(Comment::class);
    }


}
