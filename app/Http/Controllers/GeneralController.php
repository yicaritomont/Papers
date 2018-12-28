<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Citie;
use DB;

class GeneralController extends Controller
{
    public function datatable($model, $whereHas='none', $relations='none', $entity=null, $action=null){
        
        //El action se crea para saber que campo se va a usar como identificador en las acciones
        
        $object = 'App\\'.$model;
        
        if($relations != 'none'){

            //Dividir las relaciones en un array
            $relations = explode(',', $relations);
            
            $data = $object::query()->with($relations);
        }else{
            $data = $object::query();
        }

        if($whereHas != 'none'){
            $whereHas = explode(',', $whereHas);

            $data->whereHas($whereHas[0], function($q) use($whereHas){
                $q->where($whereHas[1], '=', $whereHas[2]);
            });
        }

        return datatables()
            // ->of($data)
            ->collection($data->get())
            ->addColumn('entity', $entity)
            ->addColumn('action', $action)
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function cities($id = null)
    {
        $countryCities = \App\Country::getCountryCitiesById($id)->pluck('name', 'id')
        ->prepend(trans('words.ChooseOption'), '0');

        return ['status' => $countryCities];

        $result = Citie::select('id', 'name')
            ->where('countries_id', '=', $id)
        ->get()->toArray();

        array_unshift($result, ['id' => '', 'name' => trans('words.ChooseOption')]);

        foreach($result as $key => $value)
        {
            $result[$key]['name'] = ucwords(mb_strtolower($result[$key]['name'], 'UTF-8'), '(' );
        }

        echo json_encode([
            'status' => $result
        ]);
       
    }

    /**
     * Devuelve un arreglo de días en base a un rango de fechas pasadas
     */
    public static function getDaysArray($start_date, $end_date)
    {
        for($i=$start_date ; $i<=$end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days")))
        {
            $fechas[] = $i;
        }

        return $fechas;
    }
}
