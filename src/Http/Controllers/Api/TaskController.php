<?php

namespace Lfgscavelli\Todolist\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lfgscavelli\Todolist\Http\Resources\TaskResource;
use Lfgscavelli\Todolist\Http\Resources\VocabulariesResource;
use App\Repositories\RepositoryInterface;
//use Illuminate\Support\Facades\Log;
use Gate;

class TaskController extends Controller
{

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->rp = $rp->setModel('Lfgscavelli\Todolist\Models\Task')->setSearchFields(['name','description']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // non consente l'aggiunta di metadati che invece vengono
        // restituiti estendendo una risorsa da Illuminate\Http\Resources\Json\ResourceCollection;

        return TaskResource::collection($this->rp->with('categories')->paginate(25));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byDate(Request $request)
    {
        // non consente l'aggiunta di metadati che invece vengono
        // restituiti estendendo una risorsa da Illuminate\Http\Resources\Json\ResourceCollection;
        // per le chiamate new TaskCollection(Task::all());
        $builder = $this->rp->getModel()->whereBetween('date', [$request->dal, $request->al]);
        // Log::info('data '.$request->dal. " - ".$request->al);
        if (!auth()->user()->isAdmin()) {
            $builder = $builder->whereHas('users', function ($query) {
                $query->where('user_id',auth()->user()->id);
            })->orWhere('user_id',auth()->user()->id);
        }
        $paginate = $builder->with('categories')->paginate(25);
        return TaskResource::collection($paginate);
    }

    public function changeState($id, $state) {
        $data['status_id'] = $state;
        $this->rp->update($id,$data);
        return response()->json(['success' => true], 200);
    }

    /**
     *
     */
    public function listCategories()
    {
        return VocabulariesResource::collection($this->listVocabularies('Lfgscavelli\Todolist\Models\Task'));
    }

    public function listVocabularies($model) {
        if (is_string($model)) {
            $class = $model;
        } elseif($model instanceof EloquentModel) {
            $class = get_class($model);
        } else {
            return null;
        }
        $service = $this->rp->setModel('Lfgscavelli\Todolist\Models\Service')->where('class',$class)->firstOrFail();
        return $service->vocabularies;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = $this->rp->find($id);
        if ($task->tags()->count()>0) $this->rp->detach($task->tags(), $task->tags()->pluck('id'));
        if ($task->categories()->count()>0) $this->rp->detach($task->categories(), $task->categories()->pluck('id'));
        if ($this->rp->delete($id)){
            //Log::info("DELETE: ".$id);
            return response()->json(['success' => true], 200);
        }
    }
}
