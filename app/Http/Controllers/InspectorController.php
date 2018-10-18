<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Inspector;
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
        $inspector =Inspector::all();

        return view('inspector.index');
    }
}
