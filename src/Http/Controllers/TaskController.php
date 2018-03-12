<?php

namespace Lfgscavelli\Todolist\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use App\Libraries\listGenerates;
use Illuminate\Validation\Rule;
use Validator;
use Lfgscavelli\Todolist\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware(['web', 'auth']);
        $this->repo = $rp->setModel('Lfgscavelli\Todolist\Models\Task')->setSearchFields(['name','description']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:tasks'; $required = 'required|';
        if ($onUpdate) { $required = ''; $filter = Rule::unique('tasks')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255'
        ]);
    }


    /**
     * Visualizza la lista dei task, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $tasks = $this->repo->paginate($request);
        $list->setModel($tasks);
        return view('todolist::list')->with(compact('$tasks','list'));
    }

    /**
     * Mostra il form per la creazione della pagina
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $task = new Task();
        return view('todolist::edit')->with(compact('task'));
    }

    /**
     * Salva l'utente nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $data = $this->iDate($data);
        $this->repo->create($data);
        return redirect('/admin/tasks')->withSuccess('Task creato correttamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        return view('todolist::show', compact('task','pag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = $this->repo->find($id);
        return view('todolist::edit', compact('task'));
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
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        $data = $this->iDate($data);
        if ($this->repo->update($id,$data)) {
            return redirect('/admin/tasks')->withSuccess('Task modificato correttamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->repo->delete($id)) {
            return redirect('/admin/tasks')->withSuccess('Task cancellato correttamente');
        }
    }

    /**
     * converto la tada nel formato per DB
     * @param array $data
     * @return array
     */
    private function iDate(Array $data) {
        if (!empty($data['date'])) {
            $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);
        } else {
            $data['date'] = null;
        }
        return $data;
    }
}
