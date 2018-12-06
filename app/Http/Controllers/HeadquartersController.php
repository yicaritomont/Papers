<?php

namespace App\Http\Controllers;

use App\Headquarters;
use App\Http\Requests\HeadquartersRequest;
use Illuminate\Http\Request;
use App\Client;
use App\Citie;
use App\Country;
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
        if(auth()->user()->hasRole('Cliente')){
            $clientAuth = auth()->user()->clients;
            return view('headquarters.index', compact('clientAuth'));
        }
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

        $countries = Country::all()->pluck('name', 'id');
        
        return view('headquarters.new', compact('clients', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeadquartersRequest $request)
    {
        if( auth()->user()->hasRole('Cliente') ){
            $request['client_id'] = auth()->user()->clients->id;
        }
        if(Client::findOrFail($request['client_id'])->status == 1){
            $headquarters = Headquarters::create($request->all());
            $headquarters->slug = md5($headquarters->id);
            $headquarters->save();

            $alert = ['success', trans_choice('words.Headquarters', 1).' '.trans('words.HasAdded')];
        }else{
            $alert = ['error', trans('words.errorClientInactive')];
        }
        

        return redirect()->route('headquarters.index')->with('alert', $alert); 
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
        if( auth()->user()->hasRole('Cliente') && auth()->user()->clients->id != $headquarters->client_id ){
            abort(403, 'This action is unauthorized.');
        }

        $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
            ->select('clients.id AS id', 'users.name AS name')
            ->where('clients.status', 1)
        ->get()->pluck('name', 'id');

        $countries = Country::all()->pluck('name', 'id');

        $cities = Citie::all()->pluck('name', 'id');
        
        return view('headquarters.edit', compact('headquarters', 'clients', 'cities', 'countries'));
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
        if( auth()->user()->hasRole('Cliente') && auth()->user()->clients->id != $headquarters->client_id ){
            abort(403, 'This action is unauthorized.');
        }

        if( auth()->user()->hasRole('Cliente') ){
            $request['client_id'] = auth()->user()->clients->id;
        }

        if(Client::findOrFail($request['client_id'])->status == 1){
            $headquarters->update($request->all());

            $alert = ['success', trans_choice('words.Headquarters', 1).' '.trans('words.HasUpdated')];
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
        if( auth()->user()->hasRole('Cliente') && auth()->user()->clients->id != $headquarters->client_id ){
            abort(403, 'This action is unauthorized.');
        }

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
        flash()->success(trans_choice('words.Headquarters', 1).' '.trans('words.HasEliminated'));
        return back(); */
    }
}
