<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Citie;
use DB;

class GeneralController extends Controller
{
    public function datatable($model, $relations='none', $entity=null, $action=null){
        
        //El action se crea para saber que campo se va a usar como identificador en las acciones
        
        $object = 'App\\'.$model;
        
        if($relations != 'none'){

            //Dividir las relaciones en un array
            $relations = explode(',', $relations);
            
            $data = $object::query()->with($relations);
        }else{
            $data = $object::query();
        }
    
        return datatables()
            ->of($data)
            ->addColumn('entity', $entity)
            ->addColumn('action', $action)
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function cities($id)
    {

        $result = Citie::select('id', 'name')
            ->where('countries_id', '=', $id)
        ->get()->toArray();

        array_unshift($result, ['id' => '', 'name' => trans('words.ChooseOption')]);

        echo json_encode([
            'status' => $result
        ]);
       
    }
}
