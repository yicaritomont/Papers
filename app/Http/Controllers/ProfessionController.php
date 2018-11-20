<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profession;
use App\Permission;

class ProfessionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profession.index');
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $profession = Profession::pluck('name', 'id');

        return view('profession.new',compact('professions'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required|unique:professions|min:2'
        ]);
        if (Profession::create($request->except('permissions'))) {

            flash('Profession has been created.');

        } else {
            flash()->error('Unable to create Profession.');
        }

        return redirect()->route('professions.index');

    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\profession  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd($type);
        $type = Profession::find($id);
        $permissions = Permission::all('name', 'id');

        return view('profession.edit', compact('type', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Get the inspector type
        
        $profession = Profession::findOrFail($id);
        $rules = array('name' => 'bail|required|unique:professions|min:2');
        if ($profession->name != $request->name) {
           $this->validate($request,$rules);
        } 
        $profession->fill($request->except('permissions'));

        $profession->save();

        flash()->success('Profession has been updated');

        return redirect()->route('professions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\profession  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Profession::findOrFail($id)->delete()) {
            echo json_encode([
                'status' => 'Profession has been deleted',
            ]);
        } else {
            echo json_encode([
                'status' => 'Profession not deleted',
            ]);
        }
    }
}
