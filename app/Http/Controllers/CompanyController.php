<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;
use DB;
use App\Inspector;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $request['roles'] = 3;

        $user = new User();
        
        $user->picture = 'images/user.png';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->get('password'));

        
        if($user->save())
        {
            $request['user_id'] = $user->id;
            $company = Company::create($request->except('name', 'email'));
            $company->slug = md5($company->id);
            $company->save();
            
            $user->syncPermissions($request, $user);
            $user->companies()->attach($company);

            flash(trans_choice('words.Company',1).' '.trans('words.HasAdded'));

            return redirect()->back();  

        }else{
            flash()->error('Unable to create company.');
            return redirect()->route('company.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        
        $users = $company->users;
        //dd($users->count());
        return view('company.show', compact('company', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $user = $company->user;
        return view('company.edit', compact('company', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$company->user->id,
            'activity' => 'required',
        ]);

        $request['roles'] = 3;

        $user = $company->user;

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        // check for password change
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->syncPermissions($request, $user);
        $user->save();

        $company->update($request->except('name', 'email'));

        flash()->success(trans_choice('words.Company',1).' '.trans('words.HasUpdated'));
        return redirect()->route('companies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if($company)
        {
		    switch ($company->status) 
		    {
                case 1 :
                    $company->status = 0; 
                    $this->inactivateInspcetors(0, $company->id);
				    break;
    			
                case 0 :
                    $company->status = 1;
                    $this->inactivateInspcetors(1, $company->id);
				    break;
    
                default :
                    $company->status = 0;
                    $this->inactivateInspcetors(0, $company->id);
			        break;
		    } 
    
		    $company->save();
            $menssage = \Lang::get('validation.MessageCreated');
            echo json_encode([
                'status' => $menssage,
            ]);
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            echo json_encode([
                'status' => $menssage,
            ]);
        }
    }

    public function inactivateInspcetors($status, $company_id){
        //Se consultan todos los inspectores de la compañia seleccionada
        $inspectors = Inspector::join('company_inspector', 'company_inspector.inspector_id', '=', 'inspectors.id')
                ->select('inspectors.*')
                ->where('company_inspector.company_id', '=', $company_id)
                ->get();

        foreach($inspectors as $inspector){
            //Si el inspector tiene una compañia
            if($inspector->companies->count() == 1){
                Inspector::where('id', $inspector->id)->update(['status' => $status]);
            }
        }
    }
}
