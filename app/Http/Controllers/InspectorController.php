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
    public function index($company_slug=null)
    {
        if(isset($company_slug)){
            $companies = Company::select('slug', 'name')->where('slug','=',$company_slug)->get();
            
            return view('inspector.index', compact('companies'));
        }


        return view('inspector.index');
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
        $cities = Citie::pluck('name','id');
        $companies = Company::pluck('name', 'id');

        return View::make('inspector.new', compact('inspectors','professions','inspector_types','countries','cities','companies'));
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
            'name'              => 'bail|required|min:2',
            'identification'    => 'required|unique:inspectors|numeric',
            'phone'             => 'required|string',
            'addres'            => 'required|string',
            'email'             => 'required|email',
            'profession_id'     => 'required',
            'inspector_type_id' => 'required'
        ]); 
        
        $inspector = new Inspector();
        $inspector->name = $request->name;
        $inspector->identification = $request->identification;
        $inspector->phone = $request->phone;
        $inspector->addres = $request->addres;
        $inspector->email = $request->email;
        $inspector->profession_id = $request->profession_id;
        $inspector->inspector_type_id = $request->inspector_type_id;
        // if (Inspector::create($request->except('permissions','companies'))) {
        if ($inspector->save()) {
            flash(trans('words.Inspectors').' '.trans('words.HasAdded'));
        } else {
            flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectors'));
        }
        // $inspector->save();
        $inspector->companies()->attach($request->companies);
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
        $cities = Citie::pluck('name','id');
        $permissions = Permission::all('name', 'id');
        $companies = Company::pluck('name', 'id');

        return view('inspector.edit', compact('inspector', 'permissions','professions','inspector_types','countries','cities', 'companies'));
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
        
        if($inspector->identification != $request->identification) {
             
            $this->validate($request, [
            'name' => 'bail|required|min:2',
            'identification' => 'required|unique:inspectors|numeric',
            'phone' => 'required|string',
            'addres' => 'required|string',
            'email' => 'required|email',
        ]);
        }
        $inspector->fill($request->except('permissions'));

        $inspector->save();
        $inspector->companies()->sync($request->companies);

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
        $inspector = Inspector::findOrFail($id);

        if($inspector)
        {
		    switch ($inspector->status) 
		    {
                case 1 :
                    $inspector->status = 0;     
				    break;
    			
                case 0 :
                    $inspector->status = 1;
				    break;
    
                default :
                    $inspector->status = 0;
			        break;
		    } 
    
		    $inspector->save();
            $menssage = \Lang::get('validation.MessageCreated');
            flash()->success($menssage);
		    return redirect()->route('inspectors.index');
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            flash()->success($menssage);
            return redirect()->route('inspectors.index');
        }

        //Antigua eliminación
       /*  if (Inspector::findOrFail($id)->delete()) {
            flash()->success(trans('words.Inspectors').' '.trans('words.HasEliminated'));
        } else {
            flash()->success(trans('words.Inspectors').' '.trans('words.NotDeleted'));
        }
        return redirect()->back(); */
    }

    public function companyTable($company){

        $result = Inspector::query()
                ->join('company_inspector', 'company_inspector.inspector_id', '=', 'inspectors.id')
                ->join('companies', 'companies.id', '=', 'company_inspector.company_id')
                ->select('inspectors.*')
                ->where('companies.slug', '=', $company)
                ->with('companies', 'profession', 'inspectorType')
                ->get();

        // dd($result);

        return datatables()
            ->of($result)
            ->addColumn('entity', 'inspectors')
            ->addColumn('action', 'id')
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
    
}
