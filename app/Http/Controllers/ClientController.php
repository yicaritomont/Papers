<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Role;
use App\Company;
use App\Contract;
use App\Headquarters;
use App\UserCompanie;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( !auth()->user()->hasRole('Admin') ){
            $request['id'] = Company::findOrFail(session()->get('Session_Company'))->slug;
        }

        if($request['id']){
            $companies = Company::with('user:id,name')->where('slug','=',$request['id'])->first();
            
            return view('client.index', compact('companies'));
        }
        
        return view('client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        return view('client.new', compact('companies'));                    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Se valida si es un usuario con rol compañia agregue al request la compañia en sesión
        if( auth()->user()->hasRole('Compania') ){
            $request['companies'] = auth()->user()->companies->pluck('id');
        }
        
        $this->validate($request, [
            'name'              => 'required',
            'identification'    => 'required',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'required|numeric',
            'cell_phone'        => 'required|numeric',
            'companies'         => 'required',
            ]);
            
        $request['roles'] = 4;

        $user = new User();
        
        $user->picture = 'images/user.png';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('secret');
            
            
        if($user->save())
        {
            
            UserController::syncPermissions($request, $user);
            $user->companies()->attach($request->companies);
            
            $client = Client::create([
                'identification'    => $request['identification'],
                'phone'             => $request['phone'],
                'cell_phone'        => $request['cell_phone'],
                'user_id'           => $user->id,
                ]);
                
            $client->slug = md5($client->id);
            $client->save(); 
                
            //Organiza la información para enviarla a la vista del correo electronico
            $busca_relacion_usuario_compania = UserCompanie::where('user_id',$user->id)->get();
            $relacion_usuario_compania = [];
            foreach ($busca_relacion_usuario_compania as $key => $value) 
            {
                $company_user = Company::find($value->company_id);
                $info_user_compania = User::where('id',$company_user->user_id)->first();
                $relacion_usuario_compania[$value->user_id] = $info_user_compania->name;
            }
            $informacion_rol = Role::where('id',4)->first();
            $datos= array(
                'nombre_persona'    => $request->name,
                'usuario'			=> $request->email,
                'contrasena'		=> 'secret',
                'perfil'			=> $informacion_rol->name,
                'usuario_nuevo'		=> 1,
                'companies'         => $relacion_usuario_compania,                    
            );
            $user = array(
                'email'=>$request->email,
                'name'=>'USUARIO'
            );
            
            //Notiificar creaión de usuario
            UserController::SendMailToNewUser($datos,$user);
            $alert = ['success', trans_choice('words.Client', 1).' '.trans('words.HasAdded')];

            return redirect()->route('clients.index')->with('alert', $alert);

        } 
        else 
        {
            $alert = ['error', trans('words.UnableCreate').' '.trans_choice('words.Client', 1)];
            return redirect()->route('clients.index')->with('alert', $alert);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $user = $client->user;
        $companies = Company::with('user')->get()->pluck('user.name', 'id');

        if(CompanyController::compareCompanySession($client->user->companies)){
            return view('client.edit', compact('user', 'client', 'companies'));
        }else{
            abort(403, 'This action is unauthorized.');
        }

        return view('client.edit', compact('user', 'client', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if( !CompanyController::compareCompanySession($client->user->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        if( !auth()->user()->hasRole('Compania') ){
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email,'.$client->user_id,
                'phone'             => 'required|numeric',
                'cell_phone'        => 'required|numeric',
                'companies'         => 'required',
            ]);
        }else{
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email,'.$client->user_id,
                'phone'             => 'required|numeric',
                'cell_phone'        => 'required|numeric',
            ]);

            $request['companies'] = auth()->user()->companies->pluck('id');
        }

        $request['roles'] = 4;

        // $user = User::findOrFail($id);
        $user = $client->user;

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        //$user->fill($request->except('roles', 'permissions', 'password'));
        
        // check for password change
        /*if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }*/
 
        UserController::syncPermissions($request, $user);
        $user->save();
        $user->companies()->sync($request->companies);

        $client->update($request->only('identification', 'phone', 'cell_phone', 'companies'));

        $alert = ['success', trans_choice('words.Client', 1).' '.trans('words.HasUpdated')];
        return redirect()->route('clients.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if( !CompanyController::compareCompanySession($client->user->companies) ){
            abort(403, 'This action is unauthorized.');        
        }

        if($client)
        {
            //Se activan o desactivan las sedes que tiene el cliente
		    switch ($client->status) 
		    {
                case 1 :
                    Headquarters::where('client_id', $client->id)->update(['status' => 0]);
                    $client->status = 0;     
				    break;
    			
                case 0 :
                    Headquarters::where('client_id', $client->id)->update(['status' => 1]);
                    $client->status = 1;
				    break;
    
                default :
                    Headquarters::where('client_id', $client->id)->update(['status' => 0]);
                    $client->status = 0;
			        break;
		    } 
    
		    $client->save();
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

    /**
     * Retorna los contractos del cliente seleccionado
     */
    public static function contracts($id = null)
    {
        $clientContracts = Client::getClientContractsById($id)->pluck('name', 'id')
        ->prepend(trans('words.ChooseOption'), '0');

        return ['status' => $clientContracts];
    }
}
