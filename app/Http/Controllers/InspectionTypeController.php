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
        $result = InspectionType::latest()->paginate();

        return view('inspection_type.index', compact('result'));
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
            'name' => 'bail|required|min:2'
        ]);
        if (InspectionType::create($request->except('permissions'))) {

            flash(' Inspection Type has been created.');

        } else {
            flash()->error('Unable to create Inspection Type.');
        }

        return redirect()->route('inspectiontypes.index');

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
        $this->validate($request, [
            'name' => 'bail|required|min:2'
        ]);

        //Get the inspector type
        $inspectiontype = InspectionType::findOrFail($id);
        
        $inspectiontype->fill($request->except('permissions'));

        $inspectiontype->save();

        flash()->success('Inspection Type has been updated');

        return redirect()->route('inspectiontypes.index');
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
            flash()->success('Inspection type has been deleted');
        } else {
            flash()->success('Inspection type not deleted');
        }
        return redirect()->back();
    }
}
