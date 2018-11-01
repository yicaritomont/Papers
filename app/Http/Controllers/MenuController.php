<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Modulo;
Use App\Permission;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $result = Menu::latest()->paginate(15);
        return view('menu.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $modulos            = Modulo::where('state',1)->pluck('name', 'id');
        $menu               = Menu::where('state',1)->whereRaw('id = menu_id')->pluck('name', 'id');
        $cuantos_menu = count($menu);
        if($cuantos_menu<=0)
        {
           $menu[1] = 'Menu Inicial';
        }

        $menu[0] = "Menu Principal";
        
        
        $permisos           = Permission::pluck('name', 'id');
        $actividades        = $this->defaultAbilities();
        $defaultItemMenu    = $this->defaultItemMenu();
        $paramenu           = array();
        
        // Se limpian los permisosm, solo parea dejar nombres de ruta
        foreach($actividades as $abilities)
        {   
            foreach($permisos as $id_permiso => $permiso)
            {            
                $permiso = str_replace($abilities.'_','',$permiso);
                $permisos[$id_permiso] = $permiso;                
            }
        }
        
        // Las rutas creadas
        foreach($permisos as $permiso)
        {
            if(!in_array($permiso,$paramenu))
            {                    
               array_push($paramenu,$permiso);
            }
        }

        // excluye las rutas por defecto
        foreach($defaultItemMenu as $default)
        {
            if(in_array($default,$paramenu))
            {                
                $val  = array_search($default, $paramenu);
                unset($paramenu[$val]);
            }
        }
        
        $urls = array_values($paramenu);    
        $url = array();
        foreach($urls as $ruta)
        {
            $url[$ruta] = $ruta;
        }
    
        return view('menu.new',compact('modulos','menu','url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //       
        
        $this->validate($request, [
            'name' => 'required|min:4',
            'url' => 'required',            
            'modulo_id' => 'required',
            'menu_id' => 'required'
        ]);

        $lastMenuHead = Menu::orderBy('id', 'desc')->first();
        $menu = new Menu();

        $menu->name   = $request->name;
        $menu->url   = $request->url;
        $menu->modulo_id   = $request->modulo_id;
        
        if($request->menu_id == 0)
        {
            $menu->menu_id = (int)$lastMenuHead->id+1;
        }
        else
        {
            $menu->menu_id  = $request->menu_id;
        }
        
        $menu->state = 1;
        
        $menu->save();
        $menssage = \Lang::get('validation.MessageCreated');
        flash()->success($menssage);
        return redirect()->route('menus.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $modulos           = Modulo::where('state',1)->pluck('name', 'id');
        $menu               = Menu::where('state',1)->whereRaw('id = menu_id')->pluck('name', 'id');
        $cuantos_menu = count($menu);
        if($cuantos_menu<=0)
        {
            $menu[1] = 'Menu Inicial';
        }
        
        
        $permisos           = Permission::pluck('name', 'id');
        $actividades        = $this->defaultAbilities();
        $defaultItemMenu    = $this->defaultItemMenu();
        $paramenu           = array();
        
        // Se limpian los permisosm, solo parea dejar nombres de ruta
        foreach($actividades as $abilities)
        {   
            foreach($permisos as $id_permiso => $permiso)
            {            
                $permiso = str_replace($abilities.'_','',$permiso);
                $permisos[$id_permiso] = $permiso;                
            }
        }
        
        // Las rutas creadas
        foreach($permisos as $permiso)
        {
            if(!in_array($permiso,$paramenu))
            {                    
               array_push($paramenu,$permiso);
            }
        }

        // excluye las rutas por defecto
        foreach($defaultItemMenu as $default)
        {
            if(in_array($default,$paramenu))
            {                
                $val  = array_search($default, $paramenu);
                unset($paramenu[$val]);
            }
        }
        
        $urls = array_values($paramenu);    
        $url = array();
        foreach($urls as $ruta)
        {
            $url[$ruta] = $ruta;
        }
        $menus = Menu::find($id);       

        return view('menu.edit', compact('modulos','menu','url','menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'name' => 'required|min:4',
            'url' => 'required',            
            'modulo_id' => 'required',
            'menu_id' => 'required'
        ]);

        $menu = Menu::findOrFail($id);

        $menu->name   = $request->name;
        $menu->url   = $request->url;
        $menu->modulo_id   = $request->modulo_id;
        $menu->menu_id  = $request->menu_id;
        $menu->state = 1;
        
        $menu->save();
        $menssage = \Lang::get('validation.MessageCreated');
        flash()->success($menssage);
        return redirect()->route('menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        
        //Valida que exista el servicio
        if($menu)
        {
		    switch ($menu->state) 
		    {
			    case 1 : $menu->state = 0;
				         $accion = 'Desactivó';
				    break;
    			
			    case 0 : $menu->state = 1;
				         $accion = 'Activó';
				    break;
    
			    default : $menu->state = 0;
    
			        break;
		    } 
    
		    $menu->save();
            $menssage = \Lang::get('validation.MessageCreated');
            flash()->success($menssage);
		    return redirect()->route('menus.index');
        }else
            {
            	$menssage = \Lang::get('validation.MessageError');
                flash()->success($menssage);
                return redirect()->route('menus.index');
            }
    }

    /*
    Funcion de apoyo para conocer los permisos que van a ir por defecto en el menu.
    */ 
    public function defaultItemMenu()
    {
        return[
            'users',
            'roles',
            'permissions',
            'modulos',
            'menus',
            'perfiles'
        ];
    }

    public function defaultAbilities()
    {
        return[
            'view',
            'add',
            'edit',
            'delete'
        ];
    }
}
