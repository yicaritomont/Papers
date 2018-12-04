<?php

namespace App\Http\Controllers;

use App\InspectorAgenda;
use App\Headquarters;
use App\Inspector;
use App\InspectionAppointment;
use App\Country;
use App\Citie;
use App\User;
use DB;
use App\Http\Requests\InspectorAgendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InspectorAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('Inspector'))
        {
            return redirect()->route('inspectoragendas.inspector', auth()->user()->inspectors->id);
        }
        // $result = InspectorAgenda::with(['inspector'])->paginate();
        $quantity = InspectorAgenda::all()->count();
        $inspectors = Inspector::with('user')->get()->pluck('user.name', 'id');
        $countries = Country::all()->pluck('name', 'id');

        return view('inspector_agenda.index', compact('quantity', 'inspectors', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $agenda = InspectorAgenda::join('inspectors', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->join('cities', 'cities.id', '=', 'inspector_agendas.city_id')
            ->join('countries', 'countries.id', '=', 'cities.countries_id')
            ->select('cities.name AS city',
                    'countries.name AS country',
                    'users.name AS inspector',
                    'start_date',
                    'end_date',
                    'inspector_id')
            ->where('inspector_agendas.slug', $slug)
        ->get()->first();

        $this->authorize('validateId', Inspector::findOrFail($agenda->inspector_id));

        echo json_encode([
            'agenda' => $agenda,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        /* $inspectorAgenda = InspectorAgenda::join('cities', 'cities.id', '=', 'inspector_agendas.city_id')
        ->select('inspector_agendas.*', 'cities.countries_id AS country_id')
        ->where('slug','=',$slug)
        ->get()[0]; */
        
        $inspectorAgenda = InspectorAgenda::with('city')
        ->where('slug','=',$slug)
        ->get()->first();
        
        $this->authorize('validateId', Inspector::findOrFail($inspectorAgenda->inspector_id));
        // dd('Paso');
/*         $headquarters = Headquarters::all();
        $inspectors = Inspector::all(); */
        echo json_encode([
            'agenda' => $inspectorAgenda,
        ]);
    }

    /**
     * Muestra las agendas de un inspector
     *
     * @param  Int $id
     * @param  String $view
     * @return view
     */
    public function inspector($id)
    {
        // dd($request['companies'] = auth()->user()->inspectors->id);
        $inspectors = Inspector::with('user')->get()->pluck('user.name', 'id');
        $countries = Country::all()->pluck('name', 'id');

        $inspector = Inspector::findOrFail($id);

        if(count($inspector->inspector_agendas) == 0)
        {
            Session::flash('alert', ['info', trans('words.AgendaEmpty')]);
        }

        /* dd(auth()->user());
        dd($inspector->user); */

        $this->authorize('validateId', $inspector);
        
        return view('inspector_agenda.index', compact('inspectors', 'countries', 'inspector'));
    }

    /**
     * Almacena un nuevo recurso por medio de ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( auth()->user()->hasRole('Inspector') ){
            $request['inspector_id'] = auth()->user()->inspectors->id;
        }

        $request->validate([
            'start_date'    => 'required|date|date_format:Y-m-d',
            'end_date'      => 'required|date|date_format:Y-m-d',
            'inspector_id'  => 'required',
            'country'       => 'required',
            'city_id'       => 'required',
        ]);

        /* Log::info(auth()->user()->inspectors->id);
        // Log::info($request['companies'] = auth()->user()->companies);
        dd('XD'); */

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date){
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }
        else
        {
            //Contadores de error para las validaciones
            $contError=0;

            //Se consulta todas las Agendas filtradas por un inspector
            $inspectorAgenda = InspectorAgenda::where('inspector_id', '=', $request->input('inspector_id'))->get();

            foreach($inspectorAgenda as $item){
                if(($request->input('end_date') < $item->end_date && $request->input('start_date') < $item->start_date && $request->input('end_date') < $item->start_date) || ($request->input('end_date') > $item->end_date && $request->input('start_date') > $item->start_date && $request->input('start_date') > $item->end_date)){
                    //Se comprueba si las horas ingresadas no se crucen con otra agenda el mismo día
                }else{
                    $contError++;   
                }
            }

            if($request->start_date < date('Y-m-d')){
                echo json_encode([
                    'error' => trans('words.DateGreater'),
                ]);
            }else{
                //Si la agenda ya esta ocupada
                if($contError > 0){
                    echo json_encode([
                        'error' => trans('words.AgendaBusy'),
                    ]);
                }else{     

                    //Si paso las validaciones cree una Agenda
                    $agenda = InspectorAgenda::create([
                        'inspector_id'  => $request['inspector_id'],
                        'city_id'       => $request['city_id'],
                        'start_date'    => $request['start_date'],
                        'end_date'      => $request['end_date'],
                    ]);
                    $agenda->slug = md5($agenda->id);
                    $agenda->save();

                    echo json_encode([
                        'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasAdded'),
                    ]);
                }
            }
        }
    }

    /**
     * Muestra los resultados de la tabla agenda para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events($id=null)
    {
        $result = InspectorAgenda::join('inspectors', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
                ->join('users', 'users.id', '=', 'inspectors.user_id')
                ->select('users.name AS title', 'start_date AS start', 'end_date AS end', 'inspector_agendas.slug', 'inspector_id');

        if($id){
            $result = $result->where('inspectors.id', $id)->get();
        }else{
            $result = $result->get();
        }

        //Se agrega la hora 23:59:59 a la fecha final para que se vea el día final correcto en el calendario
        foreach($result as $item){
            $item->end = $item->end.'T23:59:59';
        }
        echo json_encode($result);

    }

    /**
     * Actualiza un recurso específico por medio de ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return JSON
     */
    public function update(Request $request, $slug)
    {
        if( auth()->user()->hasRole('Inspector') ){
            $request['inspector_id'] = auth()->user()->inspectors->id;
        }

        if($request['drop'] != null){
            $request->validate([
                'start_date' => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d',
                'inspector_id' => 'required',
            ]);
        }else{
            $request->validate([
                'start_date' => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d',
                'inspector_id' => 'required',
                'country' => 'required',
                'city_id' => 'required',
            ]);
        }

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date){
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }
        else{

            //Contadores de error para las validaciones
            $contAgendaError=0;
            $contCitasError=0;

            /* ------------------Validacion agenda ocupada------------------- */

            //Se consulta todas las Agendas filtradas por un inspector
            $inspectorAgendas = InspectorAgenda::where([
                ['inspector_id', '=', $request->input('inspector_id')],
                ['slug', '!=', $slug]
            ])->get();

            foreach($inspectorAgendas as $agenda){
                if(($request->input('end_date') < $agenda->end_date && $request->input('start_date') < $agenda->start_date && $request->input('end_date') < $agenda->start_date) || ($request->input('end_date') > $agenda->end_date && $request->input('start_date') > $agenda->start_date && $request->input('start_date') > $agenda->end_date)){
                    //Se comprueba si las fechas ingresadas no se crucen con otra agenda del mismo inspector
                }else{
                    $contAgendaError++;   
                }
            }

            if($request->start_date < date('Y-m-d')){
                echo json_encode([
                    'error' => trans('words.DateGreater'),
                ]);
            }else{
                //Si la agenda ya esta ocupada
                if($contAgendaError > 0){
                    echo json_encode([
                        'error' => trans('words.AgendaBusy'),
                    ]); 
                }else{

                    
                    /* ------------------Validacion editar-citas------------------- */
                    
                    //Se consulta la agenda por el identificador
                    $agenda = InspectorAgenda::where('slug', '=', $slug)->get()->first();

                    
                
                    //Se consultas las citas filtrado por el inspector de la agenda a eliminar, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                    /* $citas = InspectionAppointment::where([
                        ['inspector_id', '=', $agenda->inspector_id],
                        ['appointment_states_id', '!=', 5],
                        ['appointment_states_id', '!=', 6],
                    ])->get(); */

                    //Se consultas las citas filtrado por el inspector de la agenda a eliminar, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                    $citas1 = InspectionAppointment::select('start_date', 'end_date')
                        ->where([
                            ['inspector_id', '=', $agenda->inspector_id],
                            ['appointment_states_id', '!=', 1],
                            ['appointment_states_id', '!=', 5],
                            ['appointment_states_id', '!=', 6],
                    ]);

                    //Se consultas las citas filtrado por el inspector de la agenda a eliminar, por estado solicitado y se une con la consulta anterior
                    $citas = InspectionAppointment::select('estimated_start_date AS start_date', 'estimated_end_date AS end_date')
                        ->where([
                            ['inspector_id', '=', $agenda->inspector_id],
                            ['appointment_states_id', 1],
                        ])->union($citas1)
                    ->get();
                            
                    foreach($citas as $cita){
                        /* if($agenda->date == $cita->date){
                            if($agenda->start_time <= $cita->start_time && $agenda->end_time >= $cita->end_time){
                                if($request->start_time > $cita->start_time || $request->end_time < $cita->end_time){
                                    $contCitasError++;
                                }
                            }
                        } */
                        //Se valida si la agenda a editar contiene una o más citas
                        if($cita->start_date >= $agenda->start_date && $cita->end_date <= $agenda->end_date){
                            //Comprueba si las fechas ingresadas de la agenda afectan las citas
                            if($request->start_date > $cita->start_date || $request->end_date < $cita->end_date){
                                $contCitasError++;
                            }

                            //Validar si el inspector tiene citas en esa agenda no permita cambiar de inspector la agenda
                            if($agenda->inspector_id != $request->inspector_id){
                                $contCitasError++;
                            }
                        }
                    }
                    
                    if($contCitasError>0){
                        echo json_encode([
                            'error' => trans('words.AgendaUpdateError'),
                        ]);
                    }else{
                        if($request['drop'] != null){
                            $agenda->update([
                                'start_date'    => $request['start_date'],
                                'end_date'      => $request['end_date'],
                                'inspector_id'  => $request['inspector_id'],
                            ]);
                        }else{
                            $agenda->update($request->all());
                        }
                        
                        /* echo json_encode([
                            'status' => $request->all(),
                        ]); */

                        echo json_encode([
                            'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasUpdated'),
                        ]);
                    }        
                }  
            }
        }
    }
    
    /**
     * Elimina un recurso específico por medio de ajax.
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
        
        $this->authorize('validateId', Inspector::findOrFail($agenda->inspector_id));
        
        //Se consultas las citas filtrado por el inspector de la agenda a eliminar, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
        $citas = InspectionAppointment::where([
            ['inspector_id', '=', $agenda->inspector_id],
            ['appointment_states_id', '!=', 5],
            ['appointment_states_id', '!=', 6],
        ])->get();

        //Se valida en todas que si las citas que tiene un inspector no esten en el rango de dias de la agenda
        foreach($citas as $cita){
            if($cita->start_date >= $agenda->start_date && $cita->end_date <= $agenda->end_date){
                $cont++;
            }
        }

        //Si hay una cita en la agenda
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
    }    
}
