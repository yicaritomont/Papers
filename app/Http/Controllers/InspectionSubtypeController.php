<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionSubtype;
use App\InspectionType;
use View;

class InspectionSubtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inspection_subtype.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspection_subtype = InspectionSubtype::pluck('name', 'id');

        $inspection_types = InspectionType::pluck('name','id');

        return View::make('inspection_subtype.new',compact('inspection_subtype','inspection_types'));
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
        if(InspectionSubtype::create($request->except('permision'))) {
           flash(trans('words.InspectionSubtype').' '.trans('words.HasAdded'));
        } else {
            flash()->error(trans('words.UnableCreate').' '.trans('words.InspectionSubtype'));
        }
        // $inspector->companies()->attach($request->companies);
        return redirect()->route('inspectionsubtypes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspection_subtype= InspectionSubtype::find($id);
        $inspection_types = InspectionType::pluck('name', 'id');

        return view('inspection_subtype.edit', compact('inspection_subtype', 'inspection_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inspection_subtype = InspectionSubtype::findOrFail($id);
        
        if ($inspection_subtype->name != $request->name) {
             $this->validate($request, [
                'name' => 'bail|required|unique:inspection_subtypes|min:2'
            ]);
        }
        $inspection_subtype->fill($request->except('permission'));
        $inspection_subtype->save();
        flash()->success(trans('words.InspectionSubtype').' '.trans('words.HasUpdated'));
        return redirect()->route('inspectionsubtypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (InspectionSubtype::findOrFail($id)->delete()) {
            flash()->success(trans('words.InspectionSubtype').' '.trans('words.HasEliminated'));
        } else {
            flash()->success(trans('words.InspectionSubtype').' '.trans('words.NotDeleted'));
            flash()->success('Inspection type not deleted');
        }
        return redirect()->back();
    }
}
