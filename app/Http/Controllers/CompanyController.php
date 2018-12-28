<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\Client;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;
use DB;
use App\Inspector;
use App\UserCompanie;
use App\Role;

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
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'activity' => 'required',
        ]);

        $request['roles'] = 3;

        $user = new User();
        
        $user->picture = 'images/user.png';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('secret');

        
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

            //Organiza la información para enviarla a la vista del correo electronico
            $busca_relacion_usuario_compania = UserCompanie::where('user_id',$user->id)->get();
            $relacion_usuario_compania = [];
            foreach ($busca_relacion_usuario_compania as $key => $value) 
            {
                $company_user = Company::find($value->company_id);
                $info_user_compania = User::where('id',$company_user->user_id)->first();
                $relacion_usuario_compania[$value->user_id] = $info_user_compania->name;
            }
            $informacion_rol = Role::where('id',3)->first();
            $datos= array(
                'nombre_persona'    => $request->name,
                'usuario'			=> $request->email,
                'contrasena'		=> 'secret',
                'perfil'			=> $informacion_rol->name,
                'usuario_nuevo'		=> 1,
                'companies'         => $relacion_usuario_compania,                    
            );
            $user_mail = array(
                'email'=>$request->email,
                'name'=>'USUARIO'
            );
            
            //Notiificar creaión de usuario
            UserController::SendMailToNewUser($datos,$user_mail);

            UserController::syncPermissions($request, $user);
            $user->companies()->attach($company);

            $alert = ['success', trans_choice('words.Company',1).' '.trans('words.HasAdded')];

            return redirect()->route('companies.index')->with('alert', $alert);  

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
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email,'.$company->user->id,
            'activity' => 'required',
        ]);

        $request['roles'] = 3;

        $user = $company->user;

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        // check for password change
        /*if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }*/

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

    public static function clients($id = null)
    {
        $companyClients = Company::getCompanyClientsById($id)->pluck('user.name', 'id')
        ->prepend(trans('words.ChooseOption'), '0');

        return ['status' => $companyClients];
    }

    public static function inspectors($id = null)
    {
        $companyInspectors = Company::getCompanyInspectorsById($id)->pluck('user.name', 'id')
        ->prepend(trans('words.ChooseOption'), '0');

        return ['status' => $companyInspectors];
    }

    /**
	 * Funcion para comparar la compañia es sesión con la compañia de un usuario
	 */
    public static function compareCompanySession($companies){
        // Si es usuario retorne la vista
        if(auth()->user()->hasRole('Admin'))
        {
            return true;
        }
        else
        {
            
            // Recorra las compañias del inspector a consultar y comparelas con la conpañia en sesion, si es falso devuelva un mensaje de error
            foreach($companies as $company)
            {
                if($company->id == Company::findOrFail(session()->get('Session_Company'))->id)
                {
                    return true;
                }
            }
            return false;
        }
    }
}
