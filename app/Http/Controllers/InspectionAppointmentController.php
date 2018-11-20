<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionAppointment;
use App\Inspector;
use App\AppointmentState;
use App\InspectionType; 
use App\AppointmentLocation;
use App\InspectorAgenda;
use App\Contract;
use App\Client;
use DB;
use View;
use App\Http\Requests\InspectionAppointmentRequest;
use Illuminate\Support\Facades\Log;

class InspectionAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(date('Y-m-d H:i:s'));
        // $clients = Client::pluck('identification', 'id');
        $clients = Client::with('user')->get()->pluck('user.name', 'id');
        $result = InspectionAppointment::latest()->with(['inspector', 'appointmentState', 'inspectionSubtype'])->paginate();
        $inspectors = Inspector::pluck('name', 'id');
        $contracts = Contract::pluck('name', 'id');
        $appointment_states = AppointmentState::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])->get();
        // $appointment_states = AppointmentState::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');
        return view('inspection_appointment.index',compact('result', 'inspectors', 'appointment_states', 'appointment_locations', 'inspection_types', 'contracts', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        // dd($request->except('date'));
        $inspectors = Inspector::pluck('name', 'id');
        $appointment_states = AppointmentState::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');

        if($request->all()){
            return View::make('inspection_appointment.new',compact('inspectors', 'appointment_states', 'appointment_locations', 'inspection_types'))->with('agenda', $request->all());
        }

        return View::make('inspection_appointment.new',compact('inspectors', 'appointment_states', 'appointment_locations', 'inspection_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InspectionAppointmentRequest $request)
    {
        /* echo json_encode([
            'status' => $request->all()
        ]); */

        //Contadores de error para las validaciones
        $contFecha=0;
        $contCitasError=0;

        // Se Consula las agendas filtrada por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $agenda)
        {

            //Si el rango de dias ingresado esta dentro de la agenda
            if($request->input('estimated_start_date') >= $agenda->start_date && $request->input('estimated_end_date') <= $agenda->end_date )
            {
                $contFecha++;

                //Consulte todas las citas por el inspector y fechas seleccionadas, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                $citas = InspectionAppointment::where([
                    ['inspector_id', '=', $request->input('inspector_id')],
                    ['appointment_states_id', '=', 2],
                    ['start_date', '<=', $request->input('estimated_start_date')],
                    ['end_date', '>=', $request->input('estimated_end_date')],
                ])->get();
                
                foreach($citas as $cita)
                {
                    if(($request->input('estimated_end_date') < $cita->end_date && $request->input('estimated_start_date') < $cita->start_date && $request->input('estimated_end_date') < $cita->start_date) || ($request->input('estimated_end_date') > $cita->end_date && $request->input('estimated_start_date') > $cita->start_date && $request->input('estimated_start_date') > $cita->end_date))
                    {
                        //Se comprueba si los días ingresadas no se crucen con otra cita activa
                    }else
                    {
                        $contCitasError++;
                    }
                } 
            }
        }


        //Comprueba si selecciono un día incorrecto
        if($contFecha==0)
        {
            echo json_encode([
                'error' => trans('words.IncorrectDate'),
            ]);
        }else{

            //Comprueba si los días de la cita ingresada ya esta ocupada por otra cita
            if($contCitasError>0)
            {
                echo json_encode([
                    'error' => trans('words.IncorrectAppointments'),
                ]);
            }else{
                InspectionAppointment::create([
                    'inspector_id'              => $request['inspector_id'],
                    'inspection_subtype_id'     => $request['inspection_subtype_id'],
                    'appointment_location_id'   => $request['appointment_location_id'],
                    'contract_id'               => $request['contract_id'],
                    'client_id'                 => $request['client_id'],
                    'request_date'              => date('Y-m-d H:i:s'),
                    'estimated_start_date'      => $request['estimated_start_date'],
                    'estimated_end_date'        => $request['estimated_end_date'],
                ]);
                
                echo json_encode([
                    'status' => trans('words.Inspectionappointment').' '.trans('words.HasAdded'),
                ]);
               
            }
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        setlocale(LC_TIME,app()->getLocale());

        /* $cita = InspectionAppointment::join('inspection_subtypes', 'inspection_subtypes.id', '=', 'inspection_appointments.inspection_subtype_id')
            ->join('inspection_subtypes', 'cities.id', '=', 'inspection_appointments.city_id')
            ->join('countries', 'countries.id', '=', 'cities.countries_id')
            ->select('cities.name AS city',
                    'countries.name AS country',
                    'inspectors.name AS inspector',
                    'start_date',
                    'end_date')
            ->where('inspection_appointments.slug', $slug)
        ->get()[0]; */

        $cita = InspectionAppointment::with('inspector', 'inspectionSubtype', 'client', 'contract')->where('inspection_appointments.id', $id)
        ->get()[0];

        $html = '<table class="table">
            <thead>
                <tr>
                    <th class="text-center active" colspan="2" style="font-size:2em">'.trans('words.AppointmentInformation').'</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>'.trans('words.RequestDate').': </th>
                    <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($cita->request_date))).'</td>
                </tr>';

        if($cita->appointment_states_id != 1){
            $html .= '
                <tr>
                    <th>'.trans('words.AssignmentDate').': </th>
                    <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($cita->assignment_date))).'</td>
                </tr>
                <tr>
                    <th>'.trans('words.EstimatedStartDate').': </th>
                    <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($cita->estimated_start_date))).'</td>
                </tr>
                <tr>
                    <th>'.trans('words.EstimatedEndDate').': </th>
                    <td>'.utf8_encode(strftime("%A %d %B %Y", strtotime($cita->estimated_end_date))).'</td>
                </tr>
            ';
        }

        // dd($cita->client->user->name);

        $html .= '
            <tr>
                <th>'.trans_choice('words.Inspector', 1).': </th>
                <td>'.$cita->inspector->name.'</td>
            </tr>
            <tr>
                <th>'.trans_choice('words.InspectionType', 1).': </th>
                <td>'.$cita->inspectionSubtype->inspection_types->name.'</td>
            </tr>
            <tr>
                <th>'.trans_choice('words.InspectionSubtype', 1).': </th>
                <td>'.$cita->inspectionSubtype->name.'</td>
            </tr>
            <tr>
                <th>'.trans('words.Client').': </th>
                <td>'.$cita->client->user->name.'</td>
            </tr>
            <tr>
                <th>'.trans_choice('words.Contract', 1).': </th>
                <td>'.$cita->contract->name.'</td>
            </tr>
        ';

        echo json_encode([
            'html' => $html,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* $inspection_appointment = InspectionAppointment::with('inspectionSubtype')
            ->where('id', $id)
        ->get()[0]; */

        $inspection_appointment = InspectionAppointment::with('inspectionSubtype')->find($id);

        echo json_encode([
            'cita' => $inspection_appointment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'inspector_id'  => 'required',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date',
        ]);
        
        //Contadores de error para las validaciones
        $contFecha=0;
        $contCitasError=0;

        // Se Consula las agendas filtrada por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $agenda)
        {

            //Si el rango de dias ingresado esta dentro de la agenda
            if($request->input('start_date') >= $agenda->start_date && $request->input('end_date') <= $agenda->end_date )
            {
                $contFecha++;

                //Consulte todas las citas por el inspector y fechas seleccionadas, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                $citas = InspectionAppointment::where([
                    ['inspector_id', '=', $request->input('inspector_id')],
                    ['start_date', '>=', $request->input('start_date')],
                    ['start_date', '<=', $request->input('end_date')],
                    ['id', '!=', $id],
                    ['appointment_states_id', '!=', 5],
                    ['appointment_states_id', '!=', 6],
                ])->get();
                
                foreach($citas as $cita)
                {
                    if(($request->input('end_date') < $cita->end_date && $request->input('start_date') < $cita->start_date && $request->input('end_date') < $cita->start_date) || ($request->input('end_date') > $cita->end_date && $request->input('start_date') > $cita->start_date && $request->input('start_date') > $cita->end_date))
                    {
                        //Se comprueba si los días ingresadas no se crucen con otra cita
                    }else
                    {
                        $contCitasError++;
                    }
                }
                
            }
           
        }

        //Comprueba si selecciono un día incorrecto
        if($contFecha==0)
        {
            echo json_encode([
                'error' => trans('words.IncorrectDate'),
            ]);

            Log::info('IncorrectDate');
        }else{

            //Comprueba si los días de la cita ingresada ya esta ocupada por otra cita
            if($contCitasError>0)
            {
                echo json_encode([
                    'error' => trans('words.IncorrectAppointments'),
                ]);
                Log::info('IncorrectAppointments');
            }else{
                /* echo json_encode([
                    'status' => 'OKKKK',
                ]); */
                $inspection_appointment = InspectionAppointment::findOrFail($id);

                Log::info('LLego');

                if(($request['start_date'] != $inspection_appointment->start_date) || ($request['end_date'] != $inspection_appointment->end_date) || ($request['inspector_id'] != $inspection_appointment->inspector_id)){
                    Log::info('Cita: '.$inspection_appointment);

                    //Cambie el estado de la cita a reprogramado
                    $inspection_appointment->appointment_states_id = 5;
                    $inspection_appointment->save();

                    InspectionAppointment::create([
                        'inspector_id'              => $request['inspector_id'],
                        'appointment_states_id'     => 2,
                        'appointment_location_id'   => $inspection_appointment->appointment_location_id,
                        'inspection_subtype_id'     => $inspection_appointment->inspection_subtype_id,
                        'contract_id'               => $inspection_appointment->contract_id,
                        'client_id'                 => $inspection_appointment->client_id,
                        'request_date'              => $inspection_appointment->request_date,
                        'estimated_start_date'      => $inspection_appointment->estimated_start_date,
                        'estimated_end_date'        => $inspection_appointment->estimated_end_date,
                        'assignment_date'           => date('Y-m-d H:i:s'),
                        'start_date'                => $request['start_date'],
                        'end_date'                  => $request['end_date'],
                    ]);

                    echo json_encode([
                        'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasReprogrammed'),
                    ]);

                    /* if (InspectionAppointment::create($request->except('permissions', 'inspection_type_id'))) {
                        echo json_encode([
                            'status' => trans('words.Inspectionappointment').' '.trans('words.HasReprogrammed'),
                        ]);
                    } else {
                        echo json_encode([
                            'error' => trans('words.UnableUpdated').' '.trans('words.Inspectionappointment'),
                        ]);
                    } */
                }else{
                    // Log::info('No cambio los datos');

                    echo json_encode([
                        'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasNotModified'),
                    ]);
                    /* $inspection_appointment->fill($request->except('permissions', 'inspection_type_id'));
                    $inspection_appointment->save();
                    echo json_encode([
                        'status' => trans('words.Inspectionappointment').' '.trans('words.HasUpdated'),
                    ]); */
                    
                }
            }  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inspection_appointment = InspectionAppointment::findOrFail($id);
        $inspection_appointment->appointment_states_id = 6;
        $inspection_appointment->save();
        // $inspection_appointment->delete();
        // flash()->success(trans('words.Inspectionappointment').' '.trans('words.HasEliminated'));
        // return back();
        echo json_encode([
            'status' => trans('words.Inspectionappointment').' '.trans('words.HasEliminated'),
        ]);
    }

    /**
     * Busca el inspector seleccionado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inspector($id)
    {
        $result = InspectionAppointment::where('inspector_id', '=', $id)->latest()->with(['inspector', 'appointmentState', 'inspectionType'])->paginate();
        $inspectors = Inspector::pluck('name', 'id');
        $appointment_states = AppointmentState::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');
        return view('inspection_appointment.index',compact('result', 'inspectors', 'appointment_states', 'appointment_locations', 'inspection_types', 'id'));
    }

    /**
     * Muestra los resultados de la tabla citas para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events()
    {
        $solicitadas = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->select('estimated_start_date AS start',
                'estimated_end_date AS end',
                'inspectors.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color AS className',
                'appointment_states_id')
        ->where([
            ['appointment_states_id', 1],
        ]);

        $result = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->select('start_date AS start',
                'end_date AS end',
                'inspectors.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color',
                'appointment_states_id')
        ->where('appointment_states_id', 2)
        ->orWhere('appointment_states_id', 3)
        ->orWhere('appointment_states_id', 4)
        ->union($solicitadas)
        ->get();

        //Se agrega la hora 23:59:59 a la fecha final para que se vea el día final correcto en el calendario y un alert al className para los colores de los eventos
        foreach($result as $item){
            $item->end = $item->end.'T23:59:59';
        }
        
        echo json_encode($result);      
    }

    /**
     * Muestra los subtipos de un tipo dado
     *
     * @return JSON
     */
    public function subtypes(Request $request)
    {
        
        // $result = InspectorAgenda::select(DB::raw('CONCAT(date,"T",start_time) AS start, CONCAT(date,"T",end_time) AS end, slug'))->get();
        
        /* $result = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
                ->join('inspection_types', 'inspection_types.id', '=', 'inspection_appointments.inspection_type_id')
                ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')
                ->select(DB::raw('CONCAT(date,"T",start_time) AS start, CONCAT(date,"T",end_time) AS end, inspection_appointments.id, inspection_types.name AS type, inspectors.name AS inspector, CONCAT("alert-",appointment_states.color) AS className, inspector_id, inspection_type_id, appointment_location_id, appointment_states_id'))->get();
         */
        $result = DB::table('inspection_subtypes')
                    ->select('id', 'name')
                    ->where('inspection_type_id', '=', $request->id)
                    ->get();

        echo json_encode($result);
       
    }

    public function complete(Request $request, $id)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $appointment = InspectionAppointment::find($id);

        /* InspectionAppointment::where([
            ['inspector_id', $appointment->inspector_id],
            ['appointment_states_id', 2],
        ]); */

        //Contadores de error para las validaciones
        $contFecha=0;
        $contCitasError=0;

        // Se Consula las agendas filtrada por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$appointment->inspector_id)->get();

        foreach($inspectorAgenda as $agenda)
        {

            //Si el rango de dias ingresado esta dentro de la agenda
            if($request['start_date'] >= $agenda->start_date && $request['end_date'] <= $agenda->end_date )
            {
                $contFecha++;

                //Consulte todas las citas por el inspector y fechas seleccionadas, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                $citas = InspectionAppointment::where([
                    ['inspector_id', '=', $appointment->inspector_id],
                    ['appointment_states_id', '=', 2],
                    ['start_date', '<=', $request['start_date']],
                    ['end_date', '>=', $request['end_date']],
                ])->get();
                
                foreach($citas as $cita)
                {
                    if(($request['end_date'] < $cita->end_date && $request['start_date'] < $cita->start_date && $request['end_date'] < $cita->start_date) || ($request['end_date'] > $cita->end_date && $request['start_date'] > $cita->start_date && $request['start_date'] > $cita->end_date))
                    {
                        //Se comprueba si los días ingresadas no se crucen con otra cita activa
                    }else
                    {
                        $contCitasError++;
                    }
                } 
            }
        }


        //Comprueba si selecciono un día incorrecto
        if($contFecha==0)
        {
            echo json_encode([
                'error' => trans('words.IncorrectDate'),
            ]);
        }else{

            //Comprueba si los días de la cita ingresada ya esta ocupada por otra cita
            if($contCitasError>0)
            {
                echo json_encode([
                    'error' => trans('words.IncorrectAppointments'),
                ]);
            }else{
               /*  InspectionAppointment::create([
                    'inspector_id'              => $request['inspector_id'],
                    'inspection_subtype_id'     => $request['inspection_subtype_id'],
                    'appointment_location_id'   => $request['appointment_location_id'],
                    'contract_id'               => $request['contract_id'],
                    'client_id'                 => $request['client_id'],
                    'request_date'              => date('Y-m-d H:i:s'),
                    'estimated_start_date'      => $request['estimated_start_date'],
                    'estimated_end_date'        => $request['estimated_end_date'],
                ]);
                
                echo json_encode([
                    'status' => trans('words.Inspectionappointment').' '.trans('words.HasAdded'),
                ]); */

                $appointment->update([
                    'assignment_date'       => date('Y-m-d H:i:s'),
                    'start_date'            => $request['start_date'],
                    'end_date'              => $request['end_date'],
                    'appointment_states_id' => 2,
                ]);

                echo json_encode([
                    'status' => 'Completado',
                ]);
               
            }
            
        }

        

        Log::info('Completado');
        Log::info('Request: '.$request['start_date']);
        Log::info('RequestC: '.$request['end_date']);
        Log::info('Id: '.$id);
        Log::info('appointment: '.$appointment);
    }
}
