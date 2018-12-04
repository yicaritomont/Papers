<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Company;
use App\Permission;
use App\Authorizable;
use App\UserCompanie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use Authorizable;

    private $compania;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('id')){
            $companies = Company::with('user:id,name')->where('slug','=',$request->get('id'))->get()->first();
            return view('user.index', compact('companies'));
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
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

            $alert = ['success', trans('words.User').' '.trans('words.HasAdded')];
            return redirect()->route('users.index')->with('alert', $alert);

        } 
        else 
        {
            $alert = ['error', 'Unable to create user.'];
            return redirect()->route('users.index')->with('alert', $alert);
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
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
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

        $alert = ['success', trans('words.User').' '.trans('words.HasUpdated')];

        return redirect()->route('users.index')->with('alert', $alert);
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
        Log::info('ID a destruir: '.$id);
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
            echo json_encode([
                'status' => $menssage,
            ]);
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            /* flash()->success($menssage);
            return redirect()->route('users.index'); */
            echo json_encode([
                'status' => $menssage,
            ]);
        }	
    }

    /**
     * Sync roles and permissions
     *
     * @param Request $request
     * @param $user
     * @return string
     */
    public static function syncPermissions(Request $request, $user)
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

    public function ShowMultiple()
    { 
        // Verifica nuevamente que el usuario no sea administrador
        if(Auth::user()->roles->pluck('id')[0] != 1)
        {
            $companias = UserCompanie::with('company')->where('user_id',Auth::user()->id)->where('status',1)->get();            
            return view('auth.index',compact('companias'));
        }
    }

    public function PostMultiple($id)
    {
        $company_session = Company::find($id);

        session(['Session_Company' => $id]);
        //Session::put('Session_Company', $id);
        flash()->success('Ha iniciado Sesión Exitosamente en la Compañia '.$company_session->name);
        return redirect()->to('/home');


        /*if( Session::has('qr') )
        {
            return Redirect::route('examenesequidos.index')->with('success_message','Ahora puede registrar los exámenes que le faltan al équido.');
        }else{

            return Redirect::to('alertasVeterinario')->with('success_message', 'Ha iniciado Sesión Exitosamente en la Agremiaci&oacute;n '.$asociacion_session->nombre);
        }*/
    }

    public function companyTable($company){

        $result = User::query()
                ->join('user_company', 'user_company.user_id', '=', 'users.id')
                ->join('companies', 'companies.id', '=', 'user_company.company_id')
                ->select('users.*')
                ->where('companies.slug', '=', $company)
                ->with('roles' ,'companies' ,'companies.user')
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
