<?php

namespace App\Http\Controllers;

use App\InspectorAgenda;
use App\Headquarters;
use App\Inspector;
use DB;
use App\Http\Requests\InspectorAgendaRequest;
use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class InspectorAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($view=null)
    {
        $result = InspectorAgenda::latest()->with(['inspector', 'headquarters'])->paginate();
        $headquarters = Headquarters::all();
        $inspectors = Inspector::all();
        /*
        //dd(\App\Cities::all());
        // dd($result[0]->cities->name);
        return view('inspector_agenda.index', compact('result')); */

 
        //$result = InspectorAgenda::all();
        // if($data->count()) {
        //     foreach ($data as $key => $value) {
        //         $events[] = Calendar::event(
        //             $value->id,
        //             false,
        //             new \DateTime($value->date.'T'.$value->start_time),
        //             new \DateTime($value->date.'T'.$value->end_time),
        //             null,
        //             // Add color and link on event
        //             [
        //                 // 'color' => '#ff0000',
        //                 // // 'textColor' => 'black',
        //                 // 'url' => 'pass here url and any route',
        //             ]
        //         );
        //     }
        // }
        //dd('calendar-'.Calendar::getId());
        // dd($calendar->script ());
        if(isset($view)){
            return view('inspector_agenda.list', compact('result', 'headquarters', 'inspectors'));
        }

        return view('inspector_agenda.index', compact('result', 'headquarters', 'inspectors'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("XD");
        /* $headquarters = Headquarters::select(DB::raw('CONCAT(name ," ", address) AS nombre_completo'), 'headquarters.id AS id')->get();
        dd($headquarters->pluck('nombre_completo', 'id')); */
        $headquarters = Headquarters::all();
        $inspectors = Inspector::all();
        //dd($c[0]->name);
        return view('inspector_agenda.new', compact(['headquarters', 'inspectors']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InspectorAgendaRequest $request)
    {
        $inspectorAgenda = InspectorAgenda::create($request->all());
        $inspectorAgenda->slug = md5($inspectorAgenda->id);
        $inspectorAgenda->save();
        // $inspectorAgenda->save();
        // $request->user()->posts()->create($request->all());
        
        flash(trans('words.InspectorAgenda').' '.trans('words.HasAdded'));

        return redirect()->back(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function show(InspectorAgenda $inspectorAgenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
        $headquarters = Headquarters::all();
        $inspectors = Inspector::all();
        return view('inspector_agenda.edit', compact(['inspectorAgenda', 'headquarters', 'inspectors']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function update(InspectorAgendaRequest $request, $slug)
    {
        $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];

        $inspectorAgenda->update($request->all());

        flash()->success(trans('words.InspectorAgenda').' '.trans('words.HasUpdated'));
        return redirect()->route('inspectoragendas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
        $inspectorAgenda->delete();
        flash()->success(trans('words.InspectorAgenda').' '.trans('words.HasEliminated'));
        return back();
    }

    /**
     * Muestra las agendas de un inspector
     *
     * @param  Int $id
     * @return view
     */
    public function inspector($id)
    {
        $inspector = Inspector::where('id','=',$id)->get();
        $result = $inspector[0]->inspector_agendas;
        // dd($result);
        return view('inspector_agenda.list', compact('result', 'inspector'));
    }
}
