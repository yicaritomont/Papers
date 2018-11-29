<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preformato;
use App\InspectionSubtype;

class PreformatoController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Preformato::all();

        return view('preformato.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $preformato = Preformato::pluck('name', 'id');
        $inspection_subtypes = InspectionSubtype::pluck('name', 'id');

        return view('preformato.new', compact('preformato','inspection_subtypes'));
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
            'name' => 'required|unique:preformatos|min:2',
            'inspection_subtype_id' => 'required',
            'format' => 'required',
        ]);

        $preformato = new Preformato();
        $preformato->name = $request->name;
        $preformato->inspection_subtype_id = $request->inspection_subtype_id;
        $preformato->format = $request->format;
        $preformato->state = 1;

        if ($preformato->save()) {
            $alert = ['success', trans_choice('words.Preformato',1).' '.trans('words.HasAdded')];
        } else {
            $alert = ['success', trans('words.UnableCreate').' '.trans_choice('words.Preformato',1)];
        }
        return redirect()->route('preformatos.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function show(Support $support)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Preformato  $preformato
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspection_subtypes = InspectionSubtype::pluck('name', 'id');
        $preformato = Preformato::find($id);
        return view('preformato.edit', compact('preformato','inspection_subtypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $preformato = Preformato::findOrFail($id);

        if ($preformato->name != $request->name)
            { $this->validate($request, [
                'name' => 'required|unique:preformatos|min:2',
                'inspection_subtype_id' => 'required',
                'preformato' => 'required',
            ]);
        }
        $preformato->update($request->except(array('_method','_token')));

        $alert = ['success', trans_choice('words.Preformato',1).' '.trans('words.HasUpdated')];
        return redirect()->route('preformatos.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
      if (Preformato::findOrFail($id)->delete()) {
          echo json_encode([
              'status' => 'Format Type has been deleted',
          ]);
      } else {
          echo json_encode([
              'status' => 'Format Type not deleted',
          ]);
      }
    }
}
