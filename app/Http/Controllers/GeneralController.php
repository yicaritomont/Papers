<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GeneralController extends Controller
{
    public function datatable($model, $entity, $action=null, $relations=null){
        //El action se crea para saber que campo se va a usar como identificador en las acciones
        $object = 'App\\'.$model;

        if($relations != null){
            // $data = $object::with($relations)->get();
            $data = $object::query()->with($relations);
        }else{
            // $data = $object::all();
            $data = $object::query();
        }
        /*// dd(Client::all());
        $result = Client::latest()->with('user');
        dd($result); */
        // dd(Client::join('users', 'users.id', '=', 'clients.user_id')->select('clients.id', 'identification', 'phone', 'cell_phone', 'slug', 'clients.status', 'clients.created_at', 'users.name AS name')->get());
        return datatables()
            // ->eloquent($object::query())
            ->of($data)
            // ->of($object::query()->with('user'))
            //->of(DB::table('permissions'))
            //->of($object::with('user'))
            //->query(DB::table('permissions'))
            // ->query(DB::table('clients')->with('user'))
            // ->query($object::with('user'))
            // ->collection($object::all())
            // ->of($object::all())
            //->of(Client::join('users', 'users.id', '=', 'clients.user_id')->select('clients.id', 'identification', 'phone', 'cell_phone', 'slug', 'clients.status', 'clients.created_at', 'users.name AS name', 'users.email')->get())
            ->addColumn('entity', $entity)
            ->addColumn('action', $action)
            ->addColumn('actions', 'shared/_actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
