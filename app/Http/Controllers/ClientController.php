<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Role;
use App\Company;
use App\Contract;
use App\Headquarters;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $result = Client::all()->count();

        return view('client.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
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
        
        //Se valida si no es un usuario con rol compañia valide el campo companies
        if( !auth()->user()->hasRole('Compañia') ){
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|min:6',
                'phone'             => 'required',
                'cell_phone'        => 'required',
                'companies'         => 'required',
            ]);
        }else{
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|min:6',
                'phone'             => 'required',
                'cell_phone'        => 'required',
            ]);

            $request['companies'] = auth()->user()->companies->pluck('id');
        }

        $request['roles'] = 4;

        $user = new User();
        
        $user->picture = 'images/user.png';
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->get('password'));
        
        if($user->save())
        {
            $this->syncPermissions($request, $user);
            $user->companies()->attach($request->companies);
                         
            $client = Client::create([
                'identification'    => $request['identification'],
                'phone'             => $request['phone'],
                'cell_phone'        => $request['cell_phone'],
                'user_id'           => $user->id,
            ]);
            
            $client->slug = md5($client->id);
            $client->save(); 
            
            flash(trans('words.Client').' '.trans('words.HasAdded'));

            return redirect()->back();

        } 
        else 
        {
            //echo "Unable to ";
            flash()->error('Unable to create user.');
            return redirect()->route('users.index');
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
        $client = Client::findOrFail($client->id);
        //dd($client);
        $user = $client->user;
        $companies = Company::pluck('name', 'id');
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
        if( !auth()->user()->hasRole('Compañia') ){
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email,'.$client->user_id,
                'phone'             => 'required',
                'cell_phone'        => 'required',
                'companies'         => 'required',
            ]);
        }else{
            $this->validate($request, [
                'name'              => 'required',
                'identification'    => 'required',
                'email'             => 'required|email|unique:users,email,'.$client->user_id,
                'phone'             => 'required',
                'cell_phone'        => 'required',
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
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $this->syncPermissions($request, $user);
        $user->save();
        $user->companies()->sync($request->companies);

        // dd($request->only('identification', 'phone', 'cell_phone'));
        //$client->user->update($request->except('password'));
        $client->update($request->only('identification', 'phone', 'cell_phone', 'companies'));

        flash()->success(trans('words.Client').' '.trans('words.HasUpdated'));
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
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
            flash()->success($menssage);
		    return redirect()->route('clients.index');
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            flash()->success($menssage);
            return redirect()->route('clients.index');
        }	
        /* $client->delete();
        flash()->success(trans('words.Client').' '.trans('words.HasEliminated'));
        return back(); */
    }

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
}
