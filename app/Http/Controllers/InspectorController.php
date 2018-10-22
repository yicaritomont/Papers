<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Permission;
use App\Inspector;
use App\Profession;
use App\InspectorType;
use View;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    use Authorizable;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result =Inspector::latest()->paginate();

        return view('inspector.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Inspector::pluck('name', 'id');

        $professions = Profession::pluck('name','id');
        $inspector_types = InspectorType::pluck('name','id');

        return View::make('inspector.new', compact('inspectors'))
            ->with('professions',$professions)
            ->with('inspector_types',$inspector_types);
    }

    
}
