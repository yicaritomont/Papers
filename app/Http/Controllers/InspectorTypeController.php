<?php

namespace App\Http\Controllers;

use App\InspectorType;
use App\Permission;
use Illuminate\Http\Request;

class InspectorTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = InspectorType::latest()->paginate();

        return view('inspector_type.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspector_types = InspectorType::pluck('name', 'id');

        return view('inspector_type.new',compact('inspector_types'));
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
            'name' => 'bail|required|unique:inspector_types|min:2'
        ]);
        if (InspectorType::create($request->except('permissions'))) {

            flash('Ispector Type has been created.');

        } else {
            flash()->error('Unable to create Inspector Type.');
        }

        return redirect()->route('inspectortypes.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function show(inspector_type $inspector_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = InspectorType::find($id);
        $permissions = Permission::all('name', 'id');

        return view('inspector_type.edit', compact('type', 'permissions'));
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
        $inspectortype = InspectorType::findOrFail($id);
        $rules = array('name' => 'bail|required|unique:inspector_types|min:2');
        if ($inspectortype->name != $request->name) {
           $this->validate($request,$rules);
        } 
        
        $inspectortype->fill($request->except('permissions'));
        
        $inspectortype->save();

        flash()->success('Inspector Type has been updated');

        return redirect()->route('inspectortypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (InspectorType::findOrFail($id)->delete()) {
            flash()->success('Inspector Type has been deleted');
        } else {
            flash()->success('Inspector Type not deleted');
        }
        return redirect()->back();
    }
}
