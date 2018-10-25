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

    public function destroy($id)
    {
      
    }

    public function changePassword(Request $request, $id)
    {
        print_r($_POST);

    }

    public function verifyPassword()
    {
        if($_GET['newPassword'] != "")
        {
            // Segun el perfil del usuario, verifica el tamaño de la contraseña
            $roles = Role::find($roles);
            $rol = $user->hasAllRoles( $roles );
            return json_encode($response = ['notificacion' => $rol]);
        }
        else
        {
            return json_encode($response = ['notificacion' => trans()]);
        }
        

    }


    /*****************************************
    *    Metodos de validacion para contraseñas
    ******************************************/

    public function getLengthPattern($pattern,$str,$len)
    {
        $number_len = 0; 
        if( preg_match_all($pattern, $str, $matches) ){
            foreach( $matches[0] AS $key => $val ){
                $number_len += strlen($val);
            }
        }
        //Verificamos si el total de coincidencias es lo requerido
        if( $number_len >= $len ){
            return true;
        }
        return false;        
    }

    /* Valida la longitud del password */ 
    public function lengthPwd( $str, $len )
    {
        if( strlen($str) >= $len ){
            return true;
        }
        return false;
    }

    /* valida que contenga n cantidad de numeros*/
    public function lengthNumber( $str , $len )
    {
        $pattern = "/([0-9]+)/";
        return $this->getLengthPattern($pattern,$str,$len);
    }

    /* Valida que contenga n cantidad de minusculas*/
    public function lengthLower( $str , $len )
    {
        $pattern = "/([a-z]+)/";
        return $this->getLengthPattern($pattern,$str,$len);        
    }

    /* valida que contenga n cantidad de mayusculas*/
    public function lengthUpper( $str, $len )
    {
        $pattern = "/([A-Z]+)/";
        return $this->getLengthPattern($pattern,$str,$len);
    }
    
    /* valida que no tenga coincidencias con palabras claves*/
    public function getKeyWords( $str , $keys = array() )
    {
        $this->obtenerInfoEmpresa();
        $band = true;
        foreach($keys AS $k => $item){
            if( isset($_SESSION[$item])){
                $val = trim($_SESSION[$item]);
                if( !empty($val) ){
                    //Dividimos el valor por espacio
                    $source = explode(" ",$_SESSION[$item]);
                    foreach( $source AS $i => $word ){
                        if( preg_match("/".$word."/i",$str,$matches) ){
                            if( isset($matches[0]) && $matches[0] != ""  ){
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return $band;
    }

}
