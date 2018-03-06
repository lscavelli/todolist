<?php

namespace Lfgscavelli\Todolist\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use App\Libraries\listGenerates;
use Illuminate\Validation\Rule;
use Validator;

class TaskController extends Controller
{

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware(['web', 'auth']);
        $this->repo = $rp->setModel('Lfgscavelli\Todolist\Models\Task')->setSearchFields(['name','description']);
    }

    /**
     * Visualizza la lista dei task, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function list(Request $request, listGenerates $list) {
        $tasks = $this->repo->paginate($request);
        $list->setModel($tasks);
        return view('todolist::list')->with(compact('$tasks','list'));
    }
}
