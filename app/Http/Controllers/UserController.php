<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Company;
use App\Permission;
use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use Authorizable;

    private $compania;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_slug=null)
    {
        if(isset($company_slug)){
            $companies = Company::select('slug', 'name')->where('companies.slug', $company_slug)->get();

            $result = User::all()->count();

            return view('user.index', compact('result', 'companies'));
        }

        // $result = User::latest()->paginate();
        $result = User::all()->count();

        return view('user.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
        $roles = Role::pluck('name', 'id');

        return view('user.new', compact('roles', 'companies'));
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
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles' => 'required|min:1',
            'companies' => 'required|min:1',
            'picture' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
        // dd($request->companies);
        $user = new User();
        // Carga imagen a destino
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $name = str_slug($request->email).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/imagenes_user');
            $imagePath = $destinationPath. "/".  $name;
            $imageBd = 'images/imagenes_user/'.$name;
            $image->move($destinationPath, $name);
            $user->picture = $imageBd;
      
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->get('password'));
        
        

        // hash password
        //$request->merge(['password' => bcrypt($request->get('password'))]);
        // Create the user
        //if ( $user = User::create($request->except('roles', 'permissions')) ) 
        if($user->save())
        {

            $this->syncPermissions($request, $user);
            $user->companies()->attach($request->companies);
            //flash('User has been created.');
            return redirect()->route('users.index')
		    	        ->with('success_message','User has been created');

        } 
        else 
        {
            //echo "Unable to ";
            flash()->error('Unable to create user.');
            return redirect()->route('users.index');
        }

        //return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "Show User";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companies = Company::pluck('name', 'id');
        $user = User::find($id);
        $roles = Role::pluck('name', 'id');
        $permissions = Permission::all('name', 'id');

        return view('user.edit', compact('user', 'roles', 'permissions', 'companies'));
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
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required|min:1',
            'companies' => 'required|min:1',
            'picture' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the user
        $user = User::findOrFail($id);

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $name = str_slug($request->email).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/imagenes_user');
            $imagePath = $destinationPath. "/".  $name;
            $imageBd = 'images/imagenes_user/'.$name;
            $image->move($destinationPath, $name);
            $user->picture = $imageBd;
      
        }
        // dd($request->companies);
        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        //$user->fill($request->except('roles', 'permissions', 'password'));
        
        // check for password change
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        // Handle the user roles
        $this->syncPermissions($request, $user);
        // dd($user->companies->toArray());
        $user->save();
        $user->companies()->sync($request->companies);

        flash()->success('User has been updated.');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function destroy($id)
    {
        /*if ( Auth::user()->id == $id ) {
            flash()->warning('Deletion of currently logged in user is not allowed :(')->important();
            return redirect()->back();
        }

        if( User::findOrFail($id)->delete() ) {
            flash()->success('User has been deleted');
        } else {
            flash()->success('User not deleted');
        }

        return redirect()->back();*/

        $user = User::find($id);
        
        //Valida que exista el servicio
        if($user)
        {
		    switch ($user->status) 
		    {
			    case 1 : $user->status = 0;
				         $accion = 'Desactivó';
				    break;
    			
			    case 0 : $user->status = 1;
				         $accion = 'Activó';
				    break;
    
			    default : $user->status = 0;
    
			        break;
		    } 
    
		    $user->save();
            $menssage = \Lang::get('validation.MessageCreated');
            flash()->success($menssage);
		    return redirect()->route('users.index');
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            flash()->success($menssage);
            return redirect()->route('users.index');
        }	
    }

    /**
     * Sync roles and permissions
     *
     * @param Request $request
     * @param $user
     * @return string
     */
    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);

        return $user;
    }

    public function companyTable($company){

        $result = User::query()
                ->join('user_company', 'user_company.user_id', '=', 'users.id')
                ->join('companies', 'companies.id', '=', 'user_company.company_id')
                ->select('users.*')
                ->where('companies.slug', '=', $company)
                ->with('roles')
                ->get();

        return datatables()
            ->of($result)
            ->addColumn('entity', 'users')
            ->addColumn('action', 'id')
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
