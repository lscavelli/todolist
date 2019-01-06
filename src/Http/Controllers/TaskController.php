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
    public function index() {
        $this->filter->addCriterion(['assignToMe' => '1','open' => '1']);
        $assToMeOpen = $this->rp->getModel()->filter($this->filter)->orderby('created_at','desc')->paginate(10, ['*'], 'page_a'); //->get()->toArray()

        $this->filter->addCriterion(['assignToMe' => '1','closed' => '1'],true);
        $assToMeClosed = $this->rp->getModel()->filter($this->filter)->orderby('created_at','desc')->paginate(10, ['*'], 'page_b'); //->get()->toArray();

        $this->filter->addCriterion(['assignToOther' => '1','open' => '1'],true);
        $assToOtherOpen = $this->rp->getModel()->filter($this->filter)->orderby('created_at','desc')->paginate(10, ['*'], 'page_c'); //->get()->toArray()

        $this->filter->addCriterion(['assignToOther' => '1','closed' => '1'],true);
        $assToOtherClosed = $this->rp->getModel()->filter($this->filter)->orderby('created_at','desc')->paginate(10, ['*'], 'page_d'); //->get()->toArray()

        $listToMeOpen = new listGenerates($assToMeOpen);
        $listToMeClosed = new listGenerates($assToMeClosed);
        $listToOtherOpen = new listGenerates($assToOtherOpen);
        $listToOtherClosed = new listGenerates($assToOtherClosed);

        return view('todolist::dash')->with(compact(
            'listToMeOpen', 'listToMeClosed', 'listToOtherOpen', 'listToOtherClosed'
            )
        );
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
    public function show($id, Request $request, listGenerates $list)
    {
        $task = $this->rp->getModel()->findOrFail($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);
        $listUsers = new listGenerates($this->rp->paginateArray($this->listUsers($id)->toArray(),10,$request->page_a,'page_a'));
        $listGroups = new listGenerates($this->rp->paginateArray($this->listGroups($id)->toArray(),10,$request->page_c,'page_c'));
        return view('todolist::show', compact('task','pag', 'listUsers', 'listGroups'));
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
        $tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        $vocabularies = $this->rp->listVocabularies($task);
        return view('todolist::edit', compact('task','tags','vocabularies'));
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
        $task = $this->rp->find($id);
        if ($this->checkAccess()) return redirect('/admin/tasks')->withErrors('Non hai i diritti di accesso');
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
        $task = $this->rp->find($id);
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
        $task = $this->rp->find($id);
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
        $task = $this->rp->find($id);
        if ($this->checkAccess()) return redirect('/admin/tasks')->withErrors('Non hai i diritti di accesso');
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
        $task = $this->rp->find($id);
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
        $task = $this->rp->find($id);
        $usersArray = is_array($users) ? $users : [$users];
        foreach ($usersArray as $userId) {
            $this->rp->detach($task->users(),$userId);
        }
        return redirect()->back();
    }

    public function saveCategories($id) {
        $this->rp->saveCategories($id);
        return redirect('admin/tasks')->withSuccess('task aggiornato correttamente');
    }

    public function changeState($id, $state) {
        $data['status_id'] = $state;
        $this->rp->update($id,$data);
        return response()->json(['success' => true], 200);
    }

    public function closed($id) {
        $data['status_id'] = 1;
        $this->filter->addCriterion(['assignTome' => '1','open' => '1']);
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $task->update($data);
        return redirect('admin/tasks')->withSuccess('task aggiornato correttamente');
    }

    public function open($id) {
        $data['status_id'] = 0;
        $this->filter->addCriterion(['assignTome' => '1','closed' => '1']);
        $task = $this->rp->getModel()->filter($this->filter)->findOrFail($id);
        $task->update($data);
        return redirect('admin/tasks')->withSuccess('task aggiornato correttamente');
    }


    public function setOrder() {
        return (new position($this->rp))->reorderDrag(['type'=>'public']);
    }

    private function checkAccess()
    {
        return Gate::denies('tasks-assign');
    }

}
