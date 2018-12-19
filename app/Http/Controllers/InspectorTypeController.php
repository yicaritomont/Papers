<?php

namespace App\Http\Controllers;

use App\InspectorType;
use App\InspectionSubtype;
use App\Permission;
use DB;
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
        return view('inspector_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspector_types = InspectorType::pluck('name', 'id');

        $inspectionSubtype = InspectionSubtype::with('inspection_types')->get()->pluck('subtype_type', 'id');

        return view('inspector_type.new',compact('inspector_types', 'inspectionSubtype'));
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
            'name' => 'bail|required|unique:inspector_types|min:2',
            'inspection_subtypes_id' => 'required',
        ]);
        if (InspectorType::create($request->except('permissions'))) {

            $alert = ['success', trans_choice('words.InspectorType',1).' '.trans('words.HasAdded')];

        } else {
            $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.InspectorType', 1)];
        }

        return redirect()->route('inspectortypes.index')->with('alert', $alert);

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
        $inspectionSubtype = InspectionSubtype::join('inspection_types', 'inspection_types.id', '=', 'inspection_type_id')
                                ->select(DB::raw('inspection_subtypes.id, CONCAT(inspection_types.name, " - ", inspection_subtypes.name) AS name'))
                                ->get()->pluck('name', 'id');
        $type = InspectorType::find($id);
        $permissions = Permission::all('name', 'id');

        return view('inspector_type.edit', compact('type', 'permissions', 'inspectionSubtype'));
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
        /* $rules = array('name' => 'bail|required|unique:inspector_types|min:2', 'inspection_subtypes_id' => 'required'); */

        /* if ($inspectortype->name != $request->name) {
           $this->validate($request,$rules);
        }  */

        $this->validate($request, [
            'name' => 'bail|required|min:2|unique:inspector_types,name,'.$inspectortype->id,
            'inspection_subtypes_id' => 'required'
        ]);
        
        $inspectortype->fill($request->except('permissions'));
        
        $inspectortype->save();

        $alert = ['success', trans_choice('words.InspectorType',1).' '.trans('words.HasUpdated')];

        return redirect()->route('inspectortypes.index')->with('alert', $alert);
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
            echo json_encode([
                'status' => 'Inspector Type has been deleted',
            ]);	
        } else {
            echo json_encode([
                'status' => 'Inspector Type not deleted',
            ]);	
        }
    }
}
