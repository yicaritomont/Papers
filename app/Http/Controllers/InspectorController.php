<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Permission;
use App\Inspector;
use App\Profession;
use App\InspectorType;
use App\Company;
use App\Citie;
use App\Country;
use App\company_inspector;
use App\User;
use App\usuario_rol;
use App\Role;
use View;
use Illuminate\Http\Request;
use QR_Code\Types\QR_Url;
use QR_Code\Types\QR_meCard;
use QR_Code\QR_Code;
use QR_Code\Types\QR_CalendarEvent;
use Url;


class InspectorController extends Controller
{
    use Authorizable;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company=null)
    {
        if(isset($company)){
            $companyObj = Company::where('slug','=',$company)->get();
            $result = $companyObj[0]->inspectors;
            // dd($result);
            return view('inspector.index', compact('result', 'companyObj'));
        }

        $result =Inspector::latest()->with(['profession','inspectorType','companies'])->paginate();

        return view('inspector.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspectors = Inspector::pluck('name', 'id');
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Citie::pluck('name','id');
        $companies = Company::pluck('name', 'id');        
        $roles = Role::where('id',2)->pluck('name', 'id');

        return View::make('inspector.new', compact('inspectors','professions','inspector_types','countries','cities','companies','roles'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        print_r($_POST);
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'identification' => 'required|numeric',
            'phone' => 'required|string',
            'addres' => 'required|string',
            'email' => 'required|email|unique:users',
            'roles' => 'required',
        ]); 
        
        // Verifica si se recibe un id de inspector 
        if($request->id_inspector != "")
        {
            $inspector = Inspector::find($request->id_inspector);            
            // Valida la relacion de la compañia con el inspector
            $validaRelacion = company_inspector::where('inspector_id',$inspector->id)->where('company_id',$request->companies)->get();
            if(count($validaRelacion)<=0)
            {
                $inspector->companies()->attach($request->companies);
                flash()->success(trans('words.RelationshipInspectorCompany'));
            }
            else
            {
                flash()->error(trans('words.InspectorCompany'));
            }           
            
        }
        else
        {
            $inspector = new Inspector();
            $inspector->name = $request->name;
            $inspector->identification = $request->identification;
            $inspector->phone = $request->phone;
            $inspector->addres = $request->addres;
            $inspector->email = $request->email;
            $inspector->profession_id = $request->profession_id;
            $inspector->inspector_type_id = $request->inspector_type_id;
          
            if ($inspector->save()) 
            {
                flash(trans('words.Inspectors').' '.trans('words.HasAdded'));
                $inspector->companies()->attach($request->companies);
            } 
            else 
            {
                flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectors'));
            }

            // Crea el usuario para el inspector
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('secret');
            $user->picture = 'images/user.png';
            //$user->save();
            if($user->save())
            {
                // Consulta el identificador del inspector
                $id_inspector_registrado = Inspector::where('identification',$request->identification)->first();
             
                // consulta el identificador del usuario creado
                $id_usuario_creado = User::where('email',$request->email)->first();
               
                $user->assignRole('inspector');
                $user->syncPermissions($request, $id_usuario_creado);
                $user->companies()->attach($request->companies);
                $usuario_rol = new usuario_rol();
                $usuario_rol->user = $id_inspector_registrado->id;
                $usuario_rol->user_id = $id_usuario_creado->id;
                $usuario_rol->rol_id = $request->roles[0];
                $usuario_rol->save();
            }
            
        }
        return redirect()->route('inspectors.index');
       
       
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspector = Inspector::find($id);
        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');
        $countries = Country::pluck('name','id');
        $cities = Citie::pluck('name','id');
        $permissions = Permission::all('name', 'id');
        $companies = Company::pluck('name', 'id');

        return view('inspector.edit', compact('inspector', 'permissions','professions','inspector_types','countries','cities', 'companies'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inspector  $inspector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2'
        ]);

        //Get the inspector
        $inspector = Inspector::findOrFail($id);
        
        if($inspector->identification != $request->identification) {
             
            $this->validate($request, [
            'name' => 'bail|required|min:2',
            'identification' => 'required|unique:inspectors|numeric',
            'phone' => 'required|string',
            'addres' => 'required|string',
            'email' => 'required|email',
        ]);
        }
        $inspector->fill($request->except('permissions'));

        $inspector->save();
        $inspector->companies()->sync($request->companies);

        flash()->success(trans('words.Inspectors').' '.trans('words.HasUpdated'));

        return redirect()->route('inspectors.index');
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inspector_type  $inspector_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Inspector::findOrFail($id)->delete()) {
            flash()->success(trans('words.Inspectors').' '.trans('words.HasEliminated'));
        } else {
            flash()->success(trans('words.Inspectors').' '.trans('words.NotDeleted'));
        }
        return redirect()->back();
    }


    /** 
     * 
    */
    public function VerifyInspector()
    {
        if($_GET['idInspector'] != "")
        {
            
            // search a inspector
            $inspector = Inspector::where('identification',$_GET['idInspector'])->get();            
            
            if(count($inspector)>0)
            {
                return json_encode($response = ['notificacion' => trans('words.InspectorExist') ,'data' => $inspector ]);
            }
            else
            {
                return json_encode($response = []);
            }
             
        }
    }

    /**
     * Funcion para consultar la infomacion del inspector tipo id card
     */
    public function IdCardInspector($id)
    {
        $infoInspector = Inspector::find($id);
        // Se trae la infomacion de loa relacion de usuario
        $usuarioInspector = usuario_rol::where('user',$infoInspector->id)->where('rol_id',2)->first();
        // Se trae la información del usuario
        $usuario = User::find($usuarioInspector->user_id);
        $code = "";
                
        return view('inspector.card', compact('infoInspector','usuario'));
       
    }

    /**
     * funcion para validar la información del inspector al realizar lectura QR
     */
    public static function qrInfoInspector($id)
    {
        //$url = new QR_Url('https://werneckbh.github.io/qr-code/');
        $url = new QR_Url($_SERVER["HTTP_HOST"].'/roles-permissions/public/validateInspector/'.$id);
        $url->setSize(4)->setMargin(2)->svg();       
            
    }
    
}
