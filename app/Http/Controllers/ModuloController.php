<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modulo;
use Lang;

class ModuloController extends Controller
{
    //
    public function index()
    {
        //$result = Post::latest()->with('user')->paginate();
        $result = Modulo::latest()->paginate(15);
        return view('modulo.index', compact('result'));
    }

    public function create()
    {
        return view('modulo.new');
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
            'name' => 'required|min:4',
        ]);

        $modulo = new Modulo();

        $modulo->name   = $request->name;
        $modulo->state = 1;
        
        $modulo->save();
        $menssage = \Lang::get('validation.MessageCreated');
        flash()->success($menssage);
        return redirect()->route('modulos.index');
        
    }

    public function edit($id)
    {
        $modulo = Modulo::find($id);       

        return view('modulo.edit', compact('modulo'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
        ]);

        $modulo = Modulo::findOrFail($id);

        $modulo->name   = $request->name;
        $modulo->state = 1;
        
        $modulo->save();
        $menssage = \Lang::get('validation.MessageCreated');
        flash()->success($menssage);
        return redirect()->route('modulos.index');
    }

    public function destroy($id)
	{
		$modulo = Modulo::find($id);
        
        //Valida que exista el servicio
        if($modulo)
        {
		    switch ($modulo->state) 
		    {
			    case 1 : $modulo->state = 0;
				         $accion = 'Desactivó';
				    break;
    			
			    case 0 : $modulo->state = 1;
				         $accion = 'Activó';
				    break;
    
			    default : $modulo->state = 0;
    
			        break;
		    } 
    
		    $modulo->save();
            $menssage = \Lang::get('validation.MessageCreated');
            flash()->success($menssage);
		    return redirect()->route('modulos.index');
        }else
            {
            	$menssage = \Lang::get('validation.MessageError');
                flash()->success($menssage);
                return redirect()->route('modulos.index');
            }		        
	}
}
