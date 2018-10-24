<?php

namespace App\Http\Controllers;

use App\Headquarters;
use App\Http\Requests\HeadquartersRequest;
use Illuminate\Http\Request;
use App\Client;
use App\Citie;

class HeadquartersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Headquarters::latest()->with(['client', 'cities'])->paginate();

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
        // $result = Headquarters::latest()->with(['client', 'cities']);
        $cl = Client::all();
        $cy = Citie::all();
        //dd($c[0]->name);
        return view('headquarters.new', compact(['cl', 'cy']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HeadquartersRequest $request)
    {

        $h = new Headquarters();
        $h->client_id = $request->input('client_id');
        $h->_id = $request->input('cities_id');
        $h->name = $request->input('name');
        $h->address = $request->input('address');
        $h->status = 1;
        $h->slug = $request->input('slug');
        $h->save();
        /* $this->validate($request, [
            'title' => 'required|min:10',
            'body' => 'required|min:20'
        ]);

        $request->user()->posts()->create($request->all());
        */
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
        $cl = Client::all();
        $cy = Citie::all();
        //dd($headquarters);
        return view('headquarters.edit', compact(['headquarters', 'cl', 'cy']));
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
        $headquarters->delete();
        flash()->success(trans('words.Headquarters').' '.trans('words.HasEliminated'));
        return back();
    }
}
