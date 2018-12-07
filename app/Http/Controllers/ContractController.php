<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Client;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( !auth()->user()->hasRole('Admin') ){
            $request['id'] = Company::findOrFail(session()->get('Session_Company'))->slug;
        }
        
        if($request->get('id')){
            $companies = Company::with('user:id,name')->where('slug', '=', $request->get('id'))->first();
            
            return view('contract.index', compact('companies'));
        }

        return view('contract.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::with('user')->get()->pluck('user.name', 'id');

        return view('contract.new', compact(['clients', 'companies']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
            'name' => 'required|min:2',
            'date' => 'required|date|date_format:Y-m-d',
            'client_id' => 'required',
            'company_id' => 'required',
        ]);

        if(Client::find($request['client_id'])->user->companies->pluck('id')->contains($request['company_id'])){
            if (Contract::create($request->except('_token'))) {

                $alert = ['success', trans_choice('words.Contract', 1).' '.trans('words.HasAdded')];
    
            } else {
                $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Contract', 1)];
            }
        }else{
            $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Contract', 1)];
        }

        return redirect()->route('contracts.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->select('clients.id AS id', 'users.name AS name')
                        ->get()
                        ->pluck('name', 'id');

        $companies = Company::with('user')->get()->pluck('user.name', 'id');

        if(CompanyController::compareCompanySession([$contract->company])){
            return view('contract.edit', compact(['contract', 'clients', 'companies']));
        }else{
            abort(403, 'This action is unauthorized.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        if( !CompanyController::compareCompanySession([$contract->company]) ){
            abort(403, 'This action is unauthorized.');        
        }

        $this->validate($request, [
            'name' => 'required|min:2',
            'date' => 'date|date_format:Y-m-d',
            'client_id' => 'required',
            'company_id' => 'required',
        ]);

        // dd($request->except('_method', '_token'));

        $contract->update($request->all());

        $alert = ['success', trans_choice('words.Contract', 1).' '.trans('words.HasUpdated')];
        return redirect()->route('contracts.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        if( !CompanyController::compareCompanySession([$contract->company]) ){
            abort(403, 'This action is unauthorized.');        
        }

        if($contract)
        {
		    switch ($contract->status)
		    {
                case 1 :
                    $contract->status = 0;
				    break;

                case 0 :
                    $contract->status = 1;
				    break;

                default :
                    $contract->status = 0;
			        break;
		    }

		    $contract->save();
            $menssage = \Lang::get('validation.MessageCreated');
            echo json_encode([
                'status' => $menssage,
            ]);
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            echo json_encode([
                'status' => $menssage,
            ]);
        }
    }

    /**
     * Filtrar el cliente del contrato seleccionado
     */
    public function clients($id = null)
    {
        if($id)
        {
            echo json_encode([
                'client' => Contract::findOrFail($id)->client->user->name,
                ]);
        }
        else
        {
            echo json_encode([
                'client' => trans('words.Select').'  '.trans_choice('words.Contract', 1)
            ]);
        }
    }
}
