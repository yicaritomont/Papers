<?php

namespace App\Http\Controllers;

use App\InspectorAgenda;
use App\Headquarters;
use App\Inspector;
use App\InspectionAppointment;
use App\Country;
use App\Citie;
use App\User;
use App\Company;
use App\InspectionSubtype;
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
    public function index(Request $request)
    {
        try{

            $countries = Country::pluck('name', 'id');
            $companies = Company::with('user')->get()->pluck('user.name', 'id');

            if(auth()->user()->hasRole('Inspector')){
                $request['id'] = auth()->user()->inspectors->id;
            }

            // Consultar a un inspector
            if($request->get('id')){

                $inspector = Inspector::findOrFail($request->get('id'));

                if(count($inspector->inspector_agendas) == 0)
                {
                    Session::flash('alert', ['info', trans('words.AgendaEmpty')]);
                }

                $this->authorize('validateId', $inspector);
                
                return view('inspector_agenda.index', compact('countries', 'inspector', 'companies'));
                
            }elseif( !auth()->user()->hasRole('Admin') ){
                // $companySlug = Company::findOrFail(session()->get('Session_Company'))->slug;;
                $company = Company::with('user:id,name')->where('id', '=', session()->get('Session_Company'))->first();

                $companySlug = $company->slug;

                $inspectors = Inspector::with('user')->whereHas('user.companies', function($q) use($companySlug){
                    $q->where('slug', '=', $companySlug);
                })->get()->pluck('user.name', 'id');

                return view('inspector_agenda.index', compact('company', 'inspectors', 'countries'));
                
            // Administrador
            }else{
                return view('inspector_agenda.index', compact('countries', 'companies'));
            }
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            return back()->withErrors($e->getMessage())->withInput();
        }  
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
     * Almacena un nuevo recurso por medio de ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if( auth()->user()->hasRole('Inspector') ){
                $request['inspector_id'] = auth()->user()->inspectors->id;
            }elseif( !auth()->user()->hasRole('Admin') ){
                if( !CompanyController::compareCompanySession(Inspector::find($request['inspector_id'])->companies) ){
                    abort(403, 'This action is unauthorized.');
                }
            }

            $request->validate([
                'start_date'    => 'required|date|date_format:Y-m-d',
                'end_date'      => 'required|date|date_format:Y-m-d',
                'inspector_id'  => 'required',
                'country'       => 'required',
                'city_id'       => 'required',
            ]);

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

                // Validar si insgreso una fecha anterior a la actual
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
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try{
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
            ->firstOrFail();
            
            $inspector = Inspector::findOrFail($agenda->inspector_id);

            $this->authorize('validateId', $inspector);

            if( !CompanyController::compareCompanySession($inspector->user->companies) ){
                abort(403, 'This action is unauthorized.');
            }

            echo json_encode([
                'agenda' => $agenda,
            ]);
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InspectorAgenda  $inspectorAgenda
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        try{
            /* $inspectorAgenda = InspectorAgenda::join('cities', 'cities.id', '=', 'inspector_agendas.city_id')
            ->select('inspector_agendas.*', 'cities.countries_id AS country_id')
            ->where('slug','=',$slug)
            ->get()[0]; */
            
            $inspectorAgenda = InspectorAgenda::with('city:id,countries_id')
                ->where('slug','=',$slug)
            ->get()->first();
            
            $this->authorize('validateId', $inspectorAgenda->inspector);

            if( !CompanyController::compareCompanySession($inspectorAgenda->inspector->user->companies) ){
                abort(403, 'This action is unauthorized.');
            }
            
            echo json_encode([
                'agenda' => $inspectorAgenda,
            ]);
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
        }
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
        try{
            if( auth()->user()->hasRole('Inspector') ){
                $request['inspector_id'] = auth()->user()->inspectors->id;
            }elseif( !auth()->user()->hasRole('Admin') ){
                if( !CompanyController::compareCompanySession(Inspector::find($request['inspector_id'])->companies) ){
                    abort(403, 'This action is unauthorized.');
                }
            }

            //Se consulta la agenda por el identificador
            $agenda = InspectorAgenda::where('slug', '=', $slug)->get()->first();

            $this->authorize('validateId', $agenda->inspector);

            if( !CompanyController::compareCompanySession($agenda->inspector->user->companies) ){
                abort(403, 'This action is unauthorized.');
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

                foreach($inspectorAgendas as $value){
                    if(($request->input('end_date') < $value->end_date && $request->input('start_date') < $value->start_date && $request->input('end_date') < $value->start_date) || ($request->input('end_date') > $value->end_date && $request->input('start_date') > $value->start_date && $request->input('start_date') > $value->end_date)){
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

                            echo json_encode([
                                'status' => trans_choice('words.InspectorAgenda', 1).' '.trans('words.HasUpdated'),
                            ]);
                        }        
                    }  
                }
            }
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
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
        try{
            //Contador de errores
            $cont=0;
            
            //Se consulta la agenda por el identificador
            $agenda = InspectorAgenda::where('slug','=',$slug)->get()->first();

            // $inspector = Inspector::findOrFail($agenda->inspector_id);
            
            $this->authorize('validateId', $agenda->inspector);

            if( !CompanyController::compareCompanySession($agenda->inspector->user->companies) ){
                abort(403, 'This action is unauthorized.');
            }
            
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
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
        }
    }

    /**
     * Muestra los resultados de la tabla agenda para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events($type=null, $id=null)
    {
        try{
            $result = InspectorAgenda::join('inspectorss', 'inspectors.id', '=', 'inspector_agendas.inspector_id')
                    ->join('users', 'users.id', '=', 'inspectors.user_id')
                    ->select('users.name AS title', 'start_date AS start', 'end_date AS end', 'inspector_agendas.slug', 'inspector_id');
            
            if($type == 'inspector'){
                $result = $result->where('inspectors.id', $id)->get();
            }elseif($type == 'company'){
                $result = $result->whereHas('inspector.user.companies', function($q) use($id){
                    $q->where('companies.id', '=', $id);
                })->get();
            }else{
                $result = $result->get();
            }

            //Se agrega la hora 23:59:59 a la fecha final para que se vea el día final correcto en el calendario
            foreach($result as $item){
                $item->end = $item->end.'T23:59:59';
            }
            echo json_encode($result);
        }catch(\Exception $e){
            // En caso de error se envia un mensaje
            abort(500, $e->getMessage());
        }
    }

    /**
     * Muestra las agendas filtradas por un subtipo
     *
     * @return JSON
     */
    public static function subtype(Request $request)
    {
        $inspector_id = $request->inspector_id;
        $subtype_id = $request->subtype_id;
        $company = ($request->company_id) ? Company::find($request->company_id)->id :  session()->get('Session_Company');

        $agendas = InspectorAgenda::with('inspector')
            ->when($subtype_id, function($q, $subtype_id){
                return $q->whereHas('inspector.inspectorType', function($q) use($subtype_id){
                    $q->where('inspection_subtypes_id', '=', $subtype_id);
                });
            })
            ->whereHas('inspector.companies', function($q) use($company){
                $q->where('companies.id', $company);
            })
            ->when($inspector_id, function($q, $inspector_id){
                return $q->where('inspector_id', $inspector_id);
            })
            ->select('start_date', 'end_date')
        ->get();

        /* dd($agendas);
        dd($request->all()); */

        $citas = InspectionAppointment::
            whereIn('appointment_states_id', [1,2,3,4])
            ->when($subtype_id, function($q, $subtype_id){
                return $q->whereHas('inspectionSubtype', function($q) use($subtype_id){
                    $q->where('id', '=', $subtype_id);
                });
            })            
            ->whereHas('contract.company', function($q) use($company){
                $q->where('id', $company);
            })
            ->when($inspector_id, function($q, $inspector_id){
                return $q->where('inspector_id', $inspector_id);
            })
        ->get()->map(function($item, $key){
            return ($item->start_date == null) ? ['start_date' => $item->estimated_start_date, 'end_date' => $item->estimated_end_date] : ['start_date' => $item->start_date, 'end_date' => $item->end_date];
        });

        // dd($citas);

        $fechasAgendas = [];
        $fechasCitas = [];

        foreach($agendas as $agenda){
            for($i=$agenda->start_date ; $i<=$agenda->end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                $fechasAgendas[] = $i;
            }
        }

        $filtroAgendas = $fechasAgendas;
        foreach($citas as $cita){
            for($i=$cita['start_date'] ; $i<=$cita['end_date'] ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){

                // Se recorre todas los días de las agendas, luego se compara con cada día de la cita, si coincide añada el día cita en un arreglo y elimine el día de la agenda
                foreach($filtroAgendas as $key => $value){
                    if($value == $i){
                        $fechasCitas[$key] = $value;
                        unset($filtroAgendas[$key]);
                        break;
                    }
                }
            }
        }

        $diasDisponibles = collect($fechasAgendas)->diffAssoc($fechasCitas)->unique();
        
        /* dd($fechasCitas);
        $diasDisponibles = collect($fechasAgendas)->diff($fechasCitas); */

        if($diasDisponibles->isEmpty()){
            return json_encode([
                'msg' => trans('words.NoMatches')
            ]);
        }else{
            return json_encode([
                'agendas' => $diasDisponibles
            ]);
        }
    }
}
