<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionType;
use App\Permission;

class InspectionTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inspection_type.index');
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspection_types = InspectionType::pluck('name', 'id');

        return view('inspection_type.new',compact('inspection_types'));
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
            'name' => 'bail|unique:inspection_types|required|min:2'
        ]);
        if (InspectionType::create($request->except('permissions'))) {

            $alert = ['success', trans_choice('words.InspectionType',1).' '.trans('words.HasAdded')];

        } else {
            $alert = ['error', trans('UnableCreate').' '.trans_choice('words.InspectionType',1)];
        }

        return redirect()->route('inspectiontypes.index')->with('alert', $alert);

    }

      /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inspection_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = InspectionType::find($id);
        $permissions = Permission::all('name', 'id');

        return view('inspection_type.edit', compact('type', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inspection_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       

        //Get the inspector type
        $inspectiontype = InspectionType::findOrFail($id);
        
        if($inspectiontype->name != $request->name)
        {
             $this->validate($request, [
                'name' => 'bail|unique:inspection_types|required|min:2'
            ]);
        }

        $inspectiontype->fill($request->except('permissions'));

        $inspectiontype->save();

        $alert = ['success', trans_choice('words.InspectionType',1).' '.trans('words.HasUpdated')];

        return redirect()->route('inspectiontypes.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inspection_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (InspectionType::findOrFail($id)->delete()) {
            echo json_encode([
                'status' => 'Inspection type has been deleted',
            ]);	
        } else {
            echo json_encode([
                'status' => 'Inspection type not deleted',
            ]);	
        }
    }
}
