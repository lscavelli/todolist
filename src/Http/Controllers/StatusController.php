<?php

namespace Lfgscavelli\Todolist\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use Validator;
use App\Services\listGenerates;
use Lfgscavelli\Todolist\Models\Status;


class StatusController extends Controller
{

    private $rp;

    public function __construct(RepositoryInterface $rp)
    {
        $this->middleware(['web', 'auth']);
        $this->rp = $rp->setModel(Status::class)->setSearchFields(['name','description']);
    }

    /**
     * Verifica i dati prima di salvarli nel db
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3',
        ]);
    }

    /**
     * Visualizza la lista delle resource
     * @param Request $request
     * @param listGenerates $list
     * @return mixed
     */
    public function index(Request $request, listGenerates $list)
    {
        $list->setPagination($this->rp->paginate($request));
        return view('shop::listReviews')->with(compact('list'));
    }

    /**
     * Mostra il form per la creazione della resource
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $review = new Review();
        $products = $this->rp->setModel(Product::class)->optionsSel();
        return view('shop::editReview')->with(compact('review','products'));
    }

    /**
     * Save the resource into DB
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['user_id'] = auth()->user()->id;
        $data['ip'] = $request->ip();
        $data['approved'] = 1;
        $this->rp->create($data);
        return redirect('/admin/reviews')->withSuccess('Recensione creata correttamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = $this->rp->find($id);
        return view('shop::editReview', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update($id,Request $request)
    {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('/admin/reviews')->withSuccess('Recensione aggiornata correttamente.');
        }
        return redirect('/admin/reviews')->withErrors('Si sono verificati degli errori durante il salvataggio');
    }

    /**
     * mette su stato approvato la risorsa
     * @param $id
     * @return mixed
     */
    public function approved($id)
    {
        $review = $this->rp->find($id);
        $review->approved = 1;
        $review->save();
        return redirect('/admin/reviews')->withSuccess('Recensione aggiornata correttamente.');
    }

    /**
     * Lista delle stato approvato la risorsa filtrate
     * @param Request $request
     * @param listGenerates $list
     * @return mixed
     */
    public function toBeApproved(Request $request, listGenerates $list)
    {
        $reviews = $this->rp->where('approved',1)->paginate($request);
        $list = $list->setPagination($reviews);
        return view('shop::listReviews')->with(compact('list'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->rp->delete($id)) {
            return redirect('/admin/reviews')->withSuccess('Recensione cancellata correttamente');
        }
        return redirect()->back()->withErrors('Si sono verificati alcuni errori');
    }

}
