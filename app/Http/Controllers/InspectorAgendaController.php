<?php

namespace App\Http\Controllers;

use App\InspectorAgenda;
use App\Headquarters;
use App\Inspector;
use App\InspectionAppointment;
use App\Country;
use App\Citie;
use DB;
use App\Http\Requests\InspectorAgendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectorAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $result = InspectorAgenda::with(['inspector'])->paginate();
        $inspectors = Inspector::with('user')->get()->pluck('user.name', 'id');
        $countries = Country::all()->pluck('name', 'id');

        return view('inspector_agenda.index', compact('result', 'inspectors', 'countries'));

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
    public function show($slug)
    {
        setlocale(LC_TIME,app()->getLocale());

        $agenda = InspectorAgenda::join('inspectors', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->join('cities', 'cities.id', '=', 'inspector_agendas.city_id')
            ->join('countries', 'countries.id', '=', 'cities.countries_id')
            ->select('cities.name AS city',
                    'countries.name AS country',
                    'users.name AS inspector',
                    'start_date',
                    'end_date')
            ->where('inspector_agendas.slug', $slug)
        ->get()[0];

        $html = '<table class="table">
            <thead>
                <tr>
                    <th class="text-center active" colspan="2" style="font-size:2em">'.trans('words.AgendaInformation').'</th>
                </tr>
            </thead>
            <tr>
                <th>'.trans('words.StartDate').': </th>
                <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($agenda->start_date))).'</td>
            </tr>
            <tr>
                <th>'.trans('words.EndDate').': </th>
                <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($agenda->end_date))).'</td>
            </tr>
            <tr>
                <th>'.trans_choice('words.Inspector', 1).': </th>
                <td>'.$agenda->inspector.'</td>
            </tr>
            <tr>
                <th>'.trans('words.Country').': </th>
                <td>'.$agenda->country.'</td>
            </tr>
            <tr>
                <th>'.trans('words.City').': </th>
                <td>'.$agenda->city.'</td>
            </tr>
        </table>';

        echo json_encode([
            'html' => $html,
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
        ->get()[0];

/*         $headquarters = Headquarters::all();
        $inspectors = Inspector::all(); */
        echo json_encode([
            'agenda' => $inspectorAgenda,
        ]);
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

    /**
     * Muestra los resultados de la tabla agenda para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events()
    {
        $result = InspectorAgenda::join('inspectors', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
                ->join('users', 'users.id', '=', 'inspectors.user_id')
                ->select('users.name AS title', 'start_date AS start', 'end_date AS end', 'inspector_agendas.slug', 'inspector_id')->get();
                
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
    public function updateAjax(Request $request, $slug)
    {
        if($request['drop'] != null){
            $request->validate([
                'start_date' => 'required',
                'end_date' => 'required',
                'inspector_id' => 'required',
            ]);
        }else{
            $request->validate([
                'start_date' => 'required',
                'end_date' => 'required',
                'inspector_id' => 'required',
                'country' => 'required',
                'city_id' => 'required',
            ]);
        }

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

    public function cities(Request $request){

        $result = Citie::select('id', 'name')
            ->where('countries_id', '=', $request->id)
        ->get();

        $city = '';

        foreach($result as $row){
            $city .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }

        echo json_encode($city);
       
    }
    
}
