<?php

namespace App\Http\Controllers;

use App\InspectorAgenda;
use App\Headquarters;
use App\Inspector;
use App\InspectionAppointment;
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
    public function index()
    {

        $result = InspectorAgenda::orderBy('date', 'desc')->with(['inspector', 'headquarters'])->paginate();
        $headquarters = Headquarters::all();
        $inspectors = Inspector::all();

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
        if(isset($_GET['view'])){
            $view = $_GET['view'];
            return view('inspector_agenda.new', compact(['headquarters', 'inspectors', 'view']));
        }
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
        /* echo json_encode([
            'request' => $request->all(),
        ]); */
        
        //Contadores de error para las validaciones
        $contFecha=0;
        $contHorasError=0;

        //Se consulta todas las Agendas filtradas por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id', '=', $request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $item){
            if($request->input('date') == $item->date){
                $contFecha++;
                if(($request->input('end_time') < $item->end_time && $request->input('start_time') < $item->start_time && $request->input('end_time') < $item->start_time) || ($request->input('end_time') > $item->end_time && $request->input('start_time') > $item->start_time && $request->input('start_time') > $item->end_time)){
                    //Se comprueba si las horas ingresadas no se crucen con otra agenda el mismo día
                }else{
                    $contHorasError++;   
                }
            }
        }

        //Comprueba si hay agendas en el día seleccionado 
        if($contFecha != 0){
            //Si la agenda ya esta ocupada
            if($contHorasError > 0){
                flash()->error(trans('words.AgendaBusy'));
                return redirect()->back()->withErrors('AgendaBusy')->withInput(); 
            }      
        }

        //Si paso las validaciones cree una Agenda
        $agenda = InspectorAgenda::create($request->all());
        $agenda->slug = md5($agenda->id);
        $agenda->save();

        flash(trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasAdded'));

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
        if(isset($_GET['view'])){
            $view = $_GET['view'];
            return view('inspector_agenda.edit', compact(['inspectorAgenda', 'headquarters', 'inspectors', 'view']));
        }
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

        //Contadores de error para las validaciones
        $contHorasError=0;
        $contCitasError=0;

        /* ------------------Validacion agenda ocupada------------------- */

        //Se consulta todas las Agendas filtradas por un inspector
        $inspectorAgendas = InspectorAgenda::where([
                                        ['inspector_id', '=', $request->input('inspector_id')],
                                        ['slug', '!=', $slug]
                                    ])->get();

        foreach($inspectorAgendas as $inspectorAgenda){
            if($request->input('date') == $inspectorAgenda->date){
                if(($request->input('end_time') < $inspectorAgenda->end_time && $request->input('start_time') < $inspectorAgenda->start_time && $request->input('end_time') < $inspectorAgenda->start_time) || ($request->input('end_time') > $inspectorAgenda->end_time && $request->input('start_time') > $inspectorAgenda->start_time && $request->input('start_time') > $inspectorAgenda->end_time)){
                    //Se comprueba si las horas ingresadas no se crucen con otra agenda el mismo día
                }else{
                    $contHorasError++;   
                }
            }
        }


        //Si la agenda ya esta ocupada
        if($contHorasError > 0){
            flash()->error(trans('words.AgendaBusy'));
            return redirect()->back()->withErrors('AgendaBusy')->withInput(); 
        }      

        /* ------------------Validacion editar-citas------------------- */

        //Se consulta la agenda por el identificador
        $agenda = InspectorAgenda::where('slug', '=', $slug)->get()[0];
        
        //Se consultas las citas filtrado por el inspector de la agenda a eliminar
        $citas = InspectionAppointment::where('inspector_id', '=', $agenda->inspector_id)->get();

        foreach($citas as $cita){
            if($agenda->date == $cita->date){
                if($agenda->start_time <= $cita->start_time && $agenda->end_time >= $cita->end_time){
                    if($request->start_time > $cita->start_time || $request->end_time < $cita->end_time){
                        $contCitasError++;
                    }
                }
            }
        }

        if($contCitasError>0){
            flash()->error(trans('words.AgendaEditError'));
            return redirect()->back()->withErrors('AgendaEditError')->withInput(); 
        }else{
            $agenda->update($request->all());

            flash()->success(trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasUpdated'));
            return redirect()->route('inspectoragendas.index');
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        //Contador de errores
        $cont=0;

        //Se consulta la agenda por el identificador
        $agenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
        
        //Se consultas las citas filtrado por el inspector de la agenda a eliminar
        $citas = InspectionAppointment::where('inspector_id', '=', $agenda->inspector_id)->get();

        foreach($citas as $cita){
            if($agenda->date == $cita->date){
                if($agenda->start_time <= $cita->start_time && $agenda->end_time >= $cita->end_time){
                    $cont++;   
                }
            }
        }

        if($cont>0){
            flash()->error(trans('words.AgendaDeleteError'));
            return redirect()->back()->withErrors('AgendaDeleteError')->withInput(); 
        }else{
            //Se llama la agenda de inspector por el slug
            $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
            $inspectorAgenda->delete();
            flash()->success(trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasEliminated'));
            return back();
        }

        
    }

    /**
     * Muestra las agendas de un inspector
     *
     * @param  Int $id
     * @param  String $view
     * @return view
     */
    public function inspector($id, $view)
    {
        if($view == 'list'){
            $result = InspectorAgenda::where('inspector_id','=',$id)->orderBy('date', 'desc')->with('inspector', 'headquarters')->paginate();
            return view('inspector_agenda.'.$view, compact('result', 'id'));
        }elseif($view == 'index'){
            /* $inspector = Inspector::where('id','=',$id)->get();
            $result = $inspector[0]->inspector_agendas; */
            $result = InspectorAgenda::where('inspector_id','=',$id)->orderBy('date', 'desc')->with('inspector', 'headquarters')->paginate();
            $headquarters = Headquarters::all();
            $inspectors = Inspector::all();
            return view('inspector_agenda.index', compact('result', 'headquarters', 'inspectors', 'id'));
        }
    }

    /**
     * Cambia a la vista tabla
     *
     * @return view
     */
    public function list(){
        $result = InspectorAgenda::latest()->with(['inspector', 'headquarters'])->paginate();
        $headquarters = Headquarters::all();
        $inspectors = Inspector::all();

        return view('inspector_agenda.list', compact('result', 'headquarters', 'inspectors'));
    }

    /**
     * Almacena un nuevo recurso por medio de ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAjax(InspectorAgendaRequest $request)
    {
        /* echo json_encode([
            'request' => $request->all(),
        ]); */
        
        //Contadores de error para las validaciones
        $contFecha=0;
        $contHorasError=0;

        //Se consulta todas las Agendas filtradas por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id', '=', $request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $item){
            if($request->input('date') == $item->date){
                $contFecha++;
                if(($request->input('end_time') < $item->end_time && $request->input('start_time') < $item->start_time && $request->input('end_time') < $item->start_time) || ($request->input('end_time') > $item->end_time && $request->input('start_time') > $item->start_time && $request->input('start_time') > $item->end_time)){
                    //Se comprueba si las horas ingresadas no se crucen con otra agenda el mismo día
                }else{
                    $contHorasError++;   
                }
            }
        }

        /* //Comprueba si hay agendas en el día seleccionado 
        if($contFecha != 0){ */
            //Si la agenda ya esta ocupada
            if($contHorasError > 0){
                echo json_encode([
                    'error' => trans('words.AgendaBusy'),
                ]);
                /* flash()->error(trans('words.AgendaBusy'));
                return redirect()->back()->withErrors('AgendaBusy')->withInput();  */
            }else{     
        /* }else{
 */
            //Si paso las validaciones cree una Agenda
            $agenda = InspectorAgenda::create($request->all());
            $agenda->slug = md5($agenda->id);
            $agenda->save();

            /* flash(trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasAdded'));

            return redirect()->back();  */

            echo json_encode([
                'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasAdded'),
            ]);
        }

       
        /* //Si paso las validaciones cree una Agenda
        $agenda = InspectorAgenda::create($request->all());
        $agenda->slug = md5($agenda->id);
        $agenda->save();

        flash(trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasAdded'));

        return redirect()->back();  */
    }

    /**
     * Muestra los resultados de la tabla agenda para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events(){
        //echo "XD";
        // $result = InspectorAgenda::select(DB::raw('CONCAT(date,"T",start_time) AS start, CONCAT(date,"T",end_time) AS end, slug'))->get();
        
        $result = InspectorAgenda::join('inspectors', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
                ->join('headquarters', 'headquarters.id', '=', 'inspector_agendas.headquarters_id')
                ->select(DB::raw('CONCAT(date,"T",start_time) AS start, CONCAT(date,"T",end_time) AS end, inspector_agendas.slug, headquarters.name AS headquarter, inspectors.name AS inspector, inspector_id, headquarters_id'))->get();
        
        echo json_encode($result);
       
    }

    /**
     * Actualiza un recurso específico por medio de ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return JSON
     */
    public function updateAjax(InspectorAgendaRequest $request, $slug)
    {

        //Contadores de error para las validaciones
        $contHorasError=0;
        $contCitasError=0;

        /* ------------------Validacion agenda ocupada------------------- */

        //Se consulta todas las Agendas filtradas por un inspector
        $inspectorAgendas = InspectorAgenda::where([
                                        ['inspector_id', '=', $request->input('inspector_id')],
                                        ['slug', '!=', $slug]
                                    ])->get();

        foreach($inspectorAgendas as $inspectorAgenda){
            if($request->input('date') == $inspectorAgenda->date){
                if(($request->input('end_time') < $inspectorAgenda->end_time && $request->input('start_time') < $inspectorAgenda->start_time && $request->input('end_time') < $inspectorAgenda->start_time) || ($request->input('end_time') > $inspectorAgenda->end_time && $request->input('start_time') > $inspectorAgenda->start_time && $request->input('start_time') > $inspectorAgenda->end_time)){
                    //Se comprueba si las horas ingresadas no se crucen con otra agenda el mismo día
                }else{
                    $contHorasError++;   
                }
            }
        }


        //Si la agenda ya esta ocupada
        if($contHorasError > 0){
            echo json_encode([
                'error' => trans('words.AgendaBusy'),
            ]); 
        }else{

            
            /* ------------------Validacion editar-citas------------------- */
            
            //Se consulta la agenda por el identificador
            $agenda = InspectorAgenda::where('slug', '=', $slug)->get()[0];
        
            //Se consultas las citas filtrado por el inspector de la agenda a eliminar
            $citas = InspectionAppointment::where('inspector_id', '=', $agenda->inspector_id)->get();
            
            foreach($citas as $cita){
                if($agenda->date == $cita->date){
                    if($agenda->start_time <= $cita->start_time && $agenda->end_time >= $cita->end_time){
                        if($request->start_time > $cita->start_time || $request->end_time < $cita->end_time){
                            $contCitasError++;
                        }
                    }
                }
            }
            
            if($contCitasError>0){
                echo json_encode([
                    'error' => trans('words.AgendaEditError'),
                ]);
            }else{
                $agenda->update($request->all());
                
                echo json_encode([
                    'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasUpdated'),
                ]);
            }        
        }      
        
    }
    
    /**
     * Elimina un recurso específico por medio de ajax.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroyAjax($slug)
    {

        //Contador de errores
        $cont=0;

        //Se consulta la agenda por el identificador
        $agenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
        
        //Se consultas las citas filtrado por el inspector de la agenda a eliminar
        $citas = InspectionAppointment::where('inspector_id', '=', $agenda->inspector_id)->get();

        foreach($citas as $cita){
            if($agenda->date == $cita->date){
                if($agenda->start_time <= $cita->start_time && $agenda->end_time >= $cita->end_time){
                    $cont++;   
                }
            }
        }

        if($cont>0){
            echo json_encode([
                'error' => trans('words.AgendaDeleteError'),
            ]);
        }else{
            //Se llama la agenda de inspector por el slug
            $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
            $inspectorAgenda->delete();
            echo json_encode([
                'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasEliminated'),
            ]);
        }

        /* //Se llama la agenda de inspector por el slug
        $inspectorAgenda = InspectorAgenda::where('slug','=',$slug)->get()[0];
        $inspectorAgenda->delete();
        echo json_encode([
            'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasEliminated'),
        ]); */
    }

}
