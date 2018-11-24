<?php

namespace App\Http\Controllers;

use App\Headquarters;
use App\Http\Requests\HeadquartersRequest;
use Illuminate\Http\Request;
use App\Client;
use App\Citie;
use DB;

class HeadquartersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('headquarters.index');
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
                        ->where('clients.status', 1)
                        ->get()
                        ->pluck('name', 'id');

        $cities = Citie::all()->pluck('name', 'id');
        
        return view('headquarters.new', compact('clients', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeadquartersRequest $request)
    {
        if(Client::findOrFail($request['client_id'])->status == 1){
            $headquarters = Headquarters::create($request->all());
            $headquarters->slug = md5($headquarters->id);
            $headquarters->save();

            $alert = ['success', trans('words.Headquarters').' '.trans('words.HasAdded')];
        }else{
            $alert = ['error', trans('words.errorClientInactive')];
        }
        

        return redirect()->back()->with('alert', $alert); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function show(Headquarters $headquarters)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function edit(Headquarters $headquarters)
    {
        $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->select('clients.id AS id', 'users.name AS name')
                        ->where('clients.status', 1)
                        ->get()
                        ->pluck('name', 'id');

        $cities = Citie::all()->pluck('name', 'id');
        
        return view('headquarters.edit', compact(['headquarters', 'clients', 'cities']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function update(HeadquartersRequest $request, Headquarters $headquarters)
    {
        if(Client::findOrFail($request['client_id'])->status == 1){
            $headquarters->update($request->all());

            $alert = ['success', trans('words.Headquarters').' '.trans('words.HasUpdated')];
        }else{
            $alert = ['error', trans('words.errorClientInactive')];
        }

        return redirect()->route('headquarters.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Headquarters  $headquarters
     * @return \Illuminate\Http\Response
     */
    public function destroy(Headquarters $headquarters)
    {
        if($headquarters)
        {
		    switch ($headquarters->status) 
		    {
                case 1 :
                    $headquarters->status = 0;     
				    break;
    			
                case 0 :
                    $headquarters->status = 1;
				    break;
    
                default :
                    $headquarters->status = 0;
			        break;
		    } 
    
		    $headquarters->save();
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

        /* $headquarters->delete();
        flash()->success(trans('words.Headquarters').' '.trans('words.HasEliminated'));
        return back(); */
    }
}
