<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\Client;
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'activity' => 'required',
        ]);

        $request['roles'] = 3;

        $user = new User();
        
        $user->picture = 'images/user.png';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->get('password'));

        
        if($user->save())
        {
            $company = Company::create([
                'address'   => $request['address'],
                'phone'     => $request['phone'],
                'activity'  => $request['activity'],
                'user_id' => $user->id    
            ]);

            $company->slug = md5($company->id);
            $company->save();
            
            UserController::syncPermissions($request, $user);
            $user->companies()->attach($company);

            $alert = ['success', trans_choice('words.Company',1).' '.trans('words.HasAdded')];

            return redirect()->back()->with('alert', $alert);  

        }else{
            $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Company',1)];
            return redirect()->route('company.index')->with('alert', $alert);
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

        UserController::syncPermissions($request, $user);
        $user->save();

        $company->update($request->except('name', 'email'));

        $alert = ['success', trans_choice('words.Company',1).' '.trans('words.HasUpdated')];
        
        return redirect()->route('companies.index')->with('alert', $alert);
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

    public function clients($id){

        $result = Client::join('users', 'users.id', '=', 'clients.user_id')
            ->join('user_company', 'user_company.user_id', '=', 'users.id')
            ->select('clients.id', 'users.name')
            ->where('user_company.company_id', $id)
        ->get()->toArray();

        array_unshift($result, ['id' => '', 'name' => trans('words.ChooseOption')]);

        echo json_encode([
            'status' => $result
        ]);
    }
}
