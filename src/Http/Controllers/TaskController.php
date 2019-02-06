<?php

namespace Lfgscavelli\Todolist\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use App\Services\listGenerates;
use Illuminate\Validation\Rule;
use Validator;
use Lfgscavelli\Todolist\Models\Task;
use Carbon\Carbon;
use App\Models\Group;
use App\Models\Content\Service;
use App\models\User;
use App\Services\position;
use Illuminate\Support\Facades\Log;
use Gate;
use Lfgscavelli\Todolist\Http\Filters\TaskFilter;

class TaskController extends Controller
{

    private $rp;
    private $filter;

    public function __construct(RepositoryInterface $rp, TaskFilter $filter)  {
        $this->middleware(['web', 'auth']);
        $this->rp = $rp->setModel('Lfgscavelli\Todolist\Models\Task')->setSearchFields(['name','description']);
        $this->filter = $filter;
        // di default posso modificare i miei task e quelli assegnati a me
        $this->filter->addCriterion(['assignToMe' => '1']);
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
            'name' => 'sometimes|required|min:3|max:255',
            'date' => 'required|date_format:d/m/Y',
        ]);
    }


    /**
     * Visualizza la lista dei task, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function reIndex(Request $request, listGenerates $list) {
        $tasks = $this->rp->paginate($request);
        $list->setPagination($tasks);
        return view('todolist::list')->with(compact('$tasks','list'));
    }

    /**
     * Visualizza la lista filtrata dei task
     * @return mixed
     */
    public function index($type="assign-to-me-open", Request $request) {
        if ($type=="assign-to-me-open") {
            $filter = ['assignToMe' => '1','open' => '1'];
        } elseif($type=='assign-to-me-closed') {
            $filter = ['assignToMe' => '1','closed' => '1'];
        } elseif($type=='assign-to-other-open') {
            $filter = ['assignToOther' => '1','open' => '1'];
        } elseif($type=='assign-to-other-closed') {
            $filter = ['assignToOther' => '1','closed' => '1'];
        } elseif($type=='open') {
            $filter = ['open' => '1'];
        } elseif($type=='closed') {
            $filter = ['closed' => '1'];
        } else {
            $filter = ['all' => '1'];
        }
        $filter['categories'] = 1;

        $this->filter->addCriterion($filter);
        $tasks = $this->rp->getModel()->filter($this->filter)->orderby('created_at','desc')->paginate(20);

        $list = new listGenerates($tasks);
        return view('todolist::listTasks')->with(compact('list'));
    }


    /**
     * Mostra il form per la creazione della pagina
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        // verifico i permessi di creazione del Task
        if (Gate::denies('tasks-create')) return redirect('/admin/tasks')->withErrorss('Non hai i permessi per questa funzione.');
        $task = new Task();
        return view('todolist::edit')->with(compact('task'));
    }

    /**
     * Salva il task nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        // verifico i permessi di creazione del Task
        if (Gate::denies('tasks-create')) return redirect('/admin/tasks')->withErrorss('Non hai i permessi per questa funzione.');
        $data = $request->all();
        $this->validator($data)->validate();
        $data = $this->iDate($data);
        $data['user_id'] = auth()->user()->id;
        $this->rp->create($data);
        return redirect('/admin/tasks')->withSuccess('Task creato correttamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // tutti possono visualizzare i task
        $task = $this->rp->getModel()->findOrFail($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);
        $listUsers = new listGenerates($this->rp->paginateArray($this->listUsers($id)->toArray(),10,$request->page_a,'page_a'));
        $listGroups = new listGenerates($this->rp->paginateArray($this->listGroups($id)->toArray(),10,$request->page_b,'page_b'));
        $comments = new listGenerates($task->comments()->paginate(10,['*'],'page_c'));
        $listFile = new listGenerates($task->files()->paginate(10, ['*'], 'page_d'));
        return view('todolist::show', compact('task','pag', 'listUsers', 'listGroups','comments','listFile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        return view('todolist::edit', compact('task'));
    }

    public function categorization($id) {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $vocabularies = $this->rp->listVocabularies($task);
        if(is_null($vocabularies)) return redirect('/admin/vocabularies')->withErrors('Aggiungere vocabolari.');
        $tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        return view('todolist::editCategorization', compact('task','tags','vocabularies'));
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
        $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        $data = $this->iDate($data);
        if ($this->rp->update($id,$data)) {
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
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        if ($task->tags()->count()>0) $this->rp->detach($task->tags(), $task->tags()->pluck('id'));
        if ($task->categories()->count()>0) $this->rp->detach($task->categories(), $task->categories()->pluck('id'));
        if ($this->rp->delete($id)) {
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

    /**
     * Visualizzo la pagina per l'assegnazione dei task ai gruppi
     * @param $id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignGroups($id, Request $request, listGenerates $list) {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);

        // Lista Gruppi a cui è assegnato il task
        // --------------------------------------
        $gAss = $this->listGroups($id);
        $groupsAss = $this->rp->paginateArray($gAss->toArray(),4,$request->page_a,'page_a');

        // Lista Gruppi a cui è possibile assegnare il task
        // ------------------------------------------------
        $groupsDis = $this->rp->paginateArray($this->rp->setModel(new Group())->all()->diff($gAss)->toArray(),4,$request->page_b,'page_b');
        return view('todolist::assignGroupTask', compact('groupsAss','groupsDis','task','pag','list'));
    }

    /**
     * Restituisce la lista dei gruppi a cui è assegnato il task
     * @param $id
     * @return mixed
     */
    public function listGroups($id)  {
        return $this->rp->find($id)->groups;
    }

    /**
     * Assegna uno o più gruppi
     * @param $id
     * @param $groups
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addGroup($id, $groups)  {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $groupsArray = is_array($groups) ? $groups : [$groups];
        foreach ($groupsArray as $groupId) {
            $this->rp->attach($task->groups(),$groupId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più gruppi
     * @param $id
     * @param $groups
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delGroup($id, $groups)  {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $groupsArray = is_array($groups) ? $groups : [$groups];
        foreach ($groupsArray as $groupId) {
            $this->rp->detach($task->groups(),$groupId);
        }
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei task agli users
     * @param $id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignUsers($id, Request $request, listGenerates $list) {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);

        // Lista users a cui è assegnato il task
        // --------------------------------------
        $uAss = $this->listUsers($id);
        $usersAss = $this->rp->paginateArray($uAss->toArray(),4,$request->page_a,'page_a');

        // Lista users a cui è possibile assegnare il task
        // ------------------------------------------------
        $usersDis = $this->rp->paginateArray($this->rp->setModel(new User())->all()->diff($uAss)->toArray(),4,$request->page_b,'page_b');
        return view('todolist::assignUserTask', compact('usersAss','usersDis','task','pag','list'));
    }

    /**
     * Restituisce la lista degli utenti a cui è assegnato il task
     * @param $id
     * @return mixed
     */
    public function listUsers($id)  {
        return $this->rp->find($id)->users;
    }

    /**
     * Assegna uno o più users
     * @param $id
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser($id, $users)  {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $usersArray = is_array($users) ? $users : [$users];
        foreach ($usersArray as $userId) {
            $this->rp->attach($task->users(),$userId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più users
     * @param $id
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delUser($id, $users)  {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $usersArray = is_array($users) ? $users : [$users];
        foreach ($usersArray as $userId) {
            $this->rp->detach($task->users(),$userId);
        }
        return redirect()->back();
    }

    public function saveCategories($id) {
        $this->rp->saveCategories($id);
        return redirect('admin/tasks/'.$id.'/edit')->withSuccess('Task aggiornato correttamente');
    }

    public function changeState($id, $state) {
        $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $data['status_id'] = $state;
        $this->rp->update($id,$data);
        return response()->json(['success' => true], 200);
    }

    public function closed($id) {
        $data['status_id'] = 2;
        $this->filter->addCriterion(['assignTome' => '1','open' => '1']);
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $task->update($data);
        return redirect('admin/tasks')->withSuccess('Task aggiornato correttamente');
    }

    public function open($id) {
        $data['status_id'] = 1;
        $this->filter->addCriterion(['assignTome' => '1','closed' => '1']);
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $task->update($data);
        return redirect('admin/tasks')->withSuccess('Task aggiornato correttamente');
    }

    public function listFiles($id) {
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $listFiles = new listGenerates($task->files()->paginate(10));
        return view('todolist::editFile', compact('task','listFiles'));
    }


    public function setOrder() {
        return (new position($this->rp))->reorderDrag(['type'=>'public']);
    }

}
