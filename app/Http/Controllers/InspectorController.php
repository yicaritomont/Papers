<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Permission;
use App\Inspector;
use App\Profession;
use App\InspectorType;
use App\Company;
use App\Citie;
use App\Country;
use View;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    use Authorizable;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company=null)
    {
        if(isset($company)){
            $cpy = Company::where('slug','=',$company)->get();
            $result = $cpy[0]->inspectors;
            dd($result);
            return view('inspector.index', compact('result', 'cpy'));
        }

        $result =Inspector::latest()->with(['profession','inspectorType'])->paginate();

        return view('inspector.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspectors = Inspector::pluck('name', 'id');
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Cities::pluck('name','id');

        return View::make('inspector.new', compact('inspectors','professions','inspector_types','countries','cities'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit();
        if (Inspector::create($request->except('permissions'))) {
            flash(trans('words.Inspectors').' '.trans('words.HasAdded'));
        } else {
            flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectors'));
        }
        return redirect()->route('inspectors.index');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspector = Inspector::find($id);
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Cities::pluck('name','id');
        $permissions = Permission::all('name', 'id');

        return view('inspector.edit', compact('inspector', 'permissions','professions','inspector_types','countries','cities'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2'
        ]);

        //Get the inspector
        $inspector = Inspector::findOrFail($id);
        
        $inspector->fill($request->except('permissions'));

        $inspector->save();

        flash()->success(trans('words.Inspectors').' '.trans('words.HasUpdated'));

        return redirect()->route('inspectors.index');
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Inspector::findOrFail($id)->delete()) {
            flash()->success(trans('words.Inspectors').' '.trans('words.HasEliminated'));
        } else {
            flash()->success(trans('words.Inspectors').' '.trans('words.NotDeleted'));
        }
        return redirect()->back();
    }
    
}
