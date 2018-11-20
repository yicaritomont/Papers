<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Client;
use App\Company;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contract.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->select('clients.id AS id', 'users.name AS name')
                        ->get()
                        ->pluck('name', 'id');

        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        /* dd($company); */
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
            'date' => 'date',
            'client_id' => 'required',
            'company_id' => 'required',
        ]);
        if (Contract::create($request->except('_token'))) {

            flash('Contract has been created.');

        } else {
            flash()->error('Unable to create Contract.');
        }

        return redirect()->back();
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

        $companies = Company::all()->pluck('name', 'id');

        return view('contract.edit', compact(['contract', 'clients', 'companies']));
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

        $this->validate($request, [
            'name' => 'required|min:2',
            'date' => 'date',
            'client_id' => 'required',
            'company_id' => 'required',
        ]);

        // dd($request->except('_method', '_token'));

        $contract->update($request->all());

        //flash()->success('Client has been updated.');
        flash()->success(trans_choice('words.Contract', 1).' '.trans('words.HasUpdated'));
        return redirect()->route('contracts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        // dd($contract);

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
}
