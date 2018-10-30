<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;
use App\LastPasswordUser;
use App\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        
        $user = User::findOrFail($id);
        if($user)
        {
            $user->password = bcrypt($request->get('password'));
            $user->save();
            $last_password = new LastPasswordUser();
            $last_password->user = $user->id;
            $last_password->password = bcrypt($request->get('password'));
            $last_password->save();

            flash()->success(trans('validation.MessageCreated'));
            return redirect()->route('perfiles.index');
        }
        else
        {
            
            flash()->success(trans('validation.MessageError'));
            return redirect()->route('perfiles.index');

        }
        

    }


    public function verifyPassword()
    {
        if($_GET['newPassword'] != "")
        {
            // Segun el perfil del usuario, verifica el tamaño de la contraseña
            $pwd = $_GET['newPassword'];
            $user = User::find(Auth::user()->id);
            $rol = $user->roles[0]['id'];
            $return = $this->isValid( $pwd , $rol );
            //print_r($return);
            if(!$return->state)
            {
                return json_encode($response =  ['message' => $return] );
            }
            else
            {
                return json_encode($response = ['notificacion' =>  'OK']);
            }
            
        }
        else
        {
            return json_encode($response = ['notificacion' => 'OK']);
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
        if( $number_len >= $len )
        {
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
        $dataUser = User::find(Auth::user()->id);
        $band = true;
        foreach($keys AS $k => $item)
        {           
            if( preg_match("/".$dataUser[$item]."/i",$str,$matches) )
            {
                if( isset($matches[0]) && $matches[0] != ""  )
                {
                    return false;
                }
            }
                    
        }
        return $band;
    }

    public function getRules( $type )
    {
        $rules = array( 'lengthPwd'     => array ('label' => 'Longitud m&iacute;nima' , 'len' => 0 ),
                        'lengthNumber'  => array ('label' => 'Cantidad de n&uacute;meros' , 'len' => 0 ),
                        'lengthLower'   => array ('label' => 'Cantidad min&uacute;sculas ' , 'len' => 0 ),
                        'lengthUpper'   => array ('label' => 'Cantidad may&uacute;sculas' , 'len' => 0 ) );

        //Obtenemos reglas segun el tipo de rol
        $ruleRol = $this->getRuleRole($type);
        //Asignamos los nuevos valores
        foreach( $rules AS $key => $item ){
            $rules[$key]['len'] = $ruleRol[$key];
        }
        return $rules;
    }

    public function getRuleRole( $role )
    {
        $vecRule = array();

        $rules = array 
        (
            //Super Administrador Sistema
            '1' => array 
            (
                'lengthPwd'     => '15',
                'lengthNumber'  => '4',
                'lengthLower'   => '3',
                'lengthUpper'   => '3'
            )
        );

        if( !isset($rules[$role]) ){
            $vecRule = array
            (
                'lengthPwd'     => '8',
                'lengthNumber'  => '2',
                'lengthLower'   => '1',
                'lengthUpper'   => '1'
            );
        }else{
            $vecRule = $rules[$role];
        }

        return $vecRule;
    }
    

    public function getError( $key , $len )
    {
        $error = array
        (
            'lengthPwd'     => trans('validation.PasswordLength').' '.$len.' '.trans('validation.Character') ,
            'lengthNumber'  => trans('validation.PasswordHas').' '.$len.' '.trans('validation.Number'),
            'lengthLower'   => trans('validation.PasswordHas').' '.$len.' '.trans('validation.Lower'),
            'lengthUpper'   => trans('validation.PasswordHas').' '.$len.' '.trans('validation.Upper'),
        );

        return isset( $error[$key] ) ? $error[$key] : "";
    }

    public function consultarPassword( $pwd )
    {
        $user = Auth::user()->id;
        // take the last 10 password
        $oldPassword = LastPasswordUser::where('user',$user)->latest()->take(10)->get();
        if(count($oldPassword)>0)
        {
            $num = 0;
            foreach($oldPassword as $registered)
            {
                if(Hash::check($pwd, $registered->password)) 
                {
                    $num ++;
                }
                
            }

            if($num <=0)
            {
                return true;
            }
            else
            {
                return false;
            }
            
        }
        else
        {
            return true;
        }
        
    }

   
    /* Metodo que invoca toda la validacion del password */
    public function isValid( $pwd , $role , $confirm = null )
    {
        $obj = (object) array('state' => false , 'message' => array() , 'len' => array() );
        $rules = $this->getRules($role);
        $band = true;
        $error = array(); $len = array();
        foreach( $rules AS $key => $item )
        {
            if( !$this->$key( $pwd , $item['len'] ) )
            {
                $band = false;
                $error[$key] = $this->getError($key,$item['len']);
            }
            $len[$key] = $item['len'];
        }
        //Se ejecuta si viene la confirmación
        if( !is_null($confirm) )
        {
            $len['confirmPass'] = null;
            if( $pwd !== $confirm || empty($pwd) ){
                $band = false;
                $error['confirmPass'] = "Las Contrase&ntilde;as deben ser totalmente iguales";
            }
        }

        //Verificamos si la contraseña ya ha sido usada anteriormente
        $len['beforePass'] = null;
        if( !$this->consultarPassword($pwd) || empty($pwd) )
        {
            $band = false;
            $error['beforePass'] = trans('validation.beforePass');
        }
        
        //Verificamos que la contraseña no contenga palabras claves en relación al usuario
        $keyWords = array('name');
        $len['keyWordPass'] = null;
        if( !$this->getKeyWords($pwd,$keyWords ) || empty($pwd))
        {   
            $band = false;
            $error['keyWordPass'] = trans('validation.keyWordPass');
        }

        $obj->state = $band;
        $obj->message = $error;
        $obj->len = $len;
        return $obj;
    }
}
