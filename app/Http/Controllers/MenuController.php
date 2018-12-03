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
        return view('menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modulos            = Modulo::where('status',1)->pluck('name', 'id');
        $menu              = Menu::where('url', null)->pluck('name', 'id')->toArray();
        $menu[0] = trans('words.MainMenu');
        
        //Ordenar el menu
        ksort($menu);        
        
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
        
        $this->validate($request, [
            'name' => 'required|min:4', 
            'menu_id' => 'required',
        ]);       
        

        if($request->menu_id != 0)
        {
            $parent = Menu::find($request->menu_id);
            if($parent->url)
            {
                $alert = ['error', \Lang::get('validation.MessageError')];
                return redirect()->route('menus.index')->with('alert', $alert);
            }
        }

        $menu = new Menu();
        $menu->name   = $request->name;
        $menu->url   = $request->url;
        $menu->icon = $request->icon;
        
        $lastMenuHead = Menu::orderBy('id', 'desc')->first();
        if($request->menu_id == 0)
        {
            $menu->menu_id = (int)$lastMenuHead->id+1;
        }
        else
        {
            $menu->menu_id  = $request->menu_id;
        }
        
        $menu->status = 1;
        
        $menu->save();
        $menssage = \Lang::get('validation.MessageCreated');
        $alert = ['success', $menssage];
        return redirect()->route('menus.index')->with('alert', $alert);
        
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
        $modulos           = Modulo::where('status',1)->pluck('name', 'id');
        $menu              = Menu::where('url', null)->pluck('name', 'id')->toArray();
        $menu[0] = trans('words.MainMenu');
        
        //Ordenar el menu
        ksort($menu);        
        
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

        //Si es del menu principal el menu_id va a ser 0
        if($menus['id'] == $menus['menu_id']) $menus['menu_id'] = 0;

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
        $menu = Menu::findOrFail($id);

        if($menu['url'])
        {
            $this->validate($request, [
                'name' => 'required|min:4',
                'menu_id' => 'required',
                'url' => 'required',
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|min:4',
                'menu_id' => 'required',
            ]);
        }

        // Si se ingresa como menú padre uno que no es desplegable mostrará error
        if($request->menu_id != 0)
        {
            $parent = Menu::find($request->menu_id);
            if($parent->url)
            {
                $alert = ['error', \Lang::get('validation.MessageError')];
                return redirect()->route('menus.index')->with('alert', $alert);
            }
        }

        // Si se esta enviando la url siendo un item desplegable mostrará error
        if(isset($request['url']) && !$menu['url']){
            $alert = ['error', \Lang::get('validation.MessageError')];
            return redirect()->route('menus.index')->with('alert', $alert);
        }

        $menu->name   = $request->name;
        $menu->url   = $request->url;
        $menu->icon = $request->icon;

        $lastMenuHead = Menu::orderBy('id', 'desc')->first();
        if($request->menu_id == 0)
        {
            $menu->menu_id = (int)$lastMenuHead->id+1;
        }
        else
        {
            $menu->menu_id  = $request->menu_id;
        }
        
        $menu->save();

        // $menssage = \Lang::get('validation.MessageCreated');
        $alert = ['success', \Lang::get('validation.MessageCreated')];
        return redirect()->route('menus.index')->with('alert', $alert);
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
		    switch ($menu->status) 
		    {
			    case 1 : $menu->status = 0;
				         $accion = 'Desactivó';
				    break;
    			
			    case 0 : $menu->status = 1;
				         $accion = 'Activó';
				    break;
    
			    default : $menu->status = 0;
    
			        break;
		    } 
    
		    $menu->save();
            $menssage = \Lang::get('validation.MessageCreated');
            echo json_encode([
                'status' => $menssage,
            ]);	
        }else
            {
            	$menssage = \Lang::get('validation.MessageError');
                echo json_encode([
                    'status' => $menssage,
                ]);	
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
