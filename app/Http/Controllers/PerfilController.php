<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;
use App\Authorizable;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    //
    public function index()
    {
        $id_usuario = Auth::user()->id;
        $user = User::find($id_usuario);
        return view('perfil.index', compact('user'));
    }

    public function show($id)
    {
        //
        $user = User::find($id);       
        return view('perfil.password', compact('user'));
    
        
    }

    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('perfil.edit', compact('user'));

        
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,            
            'picture' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

         // Get the user
        $user = User::findOrFail($id);

        if ($request->hasFile('picture')) 
        {
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
        $user->save();

        flash()->success(trans('validation.MessageCreated'));

        return redirect()->route('perfiles.index');
 
    }
}
