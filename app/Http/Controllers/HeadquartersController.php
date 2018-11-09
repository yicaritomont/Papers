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
        $result = Headquarters::latest()->with('client', 'cities')->paginate();

        //dd(\App\Cities::all());
        // dd($result[0]->cities->name);
        return view('headquarters.index', compact('result'));
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

        $cities = Citie::all()->pluck('name', 'id');
        
        return view('headquarters.new', compact(['clients', 'cities']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeadquartersRequest $request)
    {
        $headquarters = Headquarters::create($request->all());
        $headquarters->slug = md5($headquarters->id);
        $headquarters->save();

        // $request->user()->posts()->create($request->all());
        
        flash(trans('words.Headquarters').' '.trans('words.HasAdded'));

        return redirect()->back(); 
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
        //dd($request->all());

        $headquarters->update($request->all());

        //flash()->success('Client has been updated.');
        flash()->success(trans('words.Headquarters').' '.trans('words.HasUpdated'));
        return redirect()->route('headquarters.index');
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
            flash()->success($menssage);
		    return redirect()->route('headquarters.index');
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            flash()->success($menssage);
            return redirect()->route('headquarters.index');
        }	

        /* $headquarters->delete();
        flash()->success(trans('words.Headquarters').' '.trans('words.HasEliminated'));
        return back(); */
    }
}
