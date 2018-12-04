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
use App\Company;
use App\Preformato;
use App\Format;
use DB;
use View;
use App\Http\Requests\InspectionAppointmentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InspectionAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->hasRole('Inspector'))
            $request['id'] = auth()->user()->inspectors->id;

        $clients = Client::with('user')->get()->pluck('user.name', 'id');
        $quantity = InspectionAppointment::all()->count();
        $inspectors = Inspector::with('user')->get()->pluck('user.name', 'id');
        $contracts = Contract::pluck('name', 'id');
        $appointment_states = AppointmentState::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])->get();
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $preformats = Preformato::pluck('name', 'id');

        if($request->get('id')){

            $inspector = Inspector::findOrFail($request->get('id'));

            if(count($inspector->inspection_appointments) == 0)
            {
                Session::flash('alert', ['info', trans('words.AppointmentEmpty')]);
            }

            $this->authorize('validateId', $inspector);

            return view('inspection_appointment.index',compact('inspector', 'inspectors', 'appointment_states', 'appointment_locations', 'inspection_types', 'contracts', 'clients', 'companies', 'preformats'));

        }

        return view('inspection_appointment.index',compact('quantity', 'inspectors', 'appointment_states', 'appointment_locations', 'inspection_types', 'contracts', 'clients', 'companies', 'preformats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        // dd($request->except('date'));
        $inspectors = Inspector::with('user')->get()->pluck('user.name', 'id');
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

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->estimated_start_date >$request->estimated_end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }
        else
        {

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
                        'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasAdded'),
                    ]);
                
                } 
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
        $cita = InspectionAppointment::with('inspectionSubtype.inspection_types:id,name', 'client.user:id,name', 'contract:id,name', 'inspector.user:id,name')
            ->where('inspection_appointments.id', $id)
        ->first();

        
        $this->authorize('validateId', Inspector::findOrFail($cita->inspector_id));
        
        echo json_encode([
            'cita' => $cita,
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
            'start_date'    => 'required|date|date_format:Y-m-d',
            'end_date'      => 'required|date|date_format:Y-m-d',
        ]);

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }
        else
        {

            //Se valida si es una cita con estado activa
            $inspection_appointment = InspectionAppointment::findOrFail($id);

            if($inspection_appointment->appointment_states_id == 2){
        
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
                }else{

                    //Comprueba si los días de la cita ingresada ya esta ocupada por otra cita
                    if($contCitasError>0)
                    {
                        echo json_encode([
                            'error' => trans('words.IncorrectAppointments'),
                        ]);
                    }else{

                        if(($request['start_date'] != $inspection_appointment->start_date) || ($request['end_date'] != $inspection_appointment->end_date) || ($request['inspector_id'] != $inspection_appointment->inspector_id)){

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

                        }else{

                            echo json_encode([
                                'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasNotModified'),
                            ]);
                            
                        }
                    }  
                }
            }else{
                $menssage = \Lang::get('validation.MessageError');
                echo json_encode([
                    'error' => $menssage,
                ]);
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
        // flash()->success(trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasEliminated'));
        // return back();
        echo json_encode([
            'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasEliminated'),
        ]);
    }

    /**
     * Muestra los resultados de la tabla citas para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events($id=null)
    {
        /* $solicitadas = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->select('estimated_start_date AS start',
                'estimated_end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color AS className',
                'appointment_states_id',
                'format_id')
        ->where('appointment_states_id', 1);

        $result = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->select('start_date AS start',
                'end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color',
                'appointment_states_id',
                'format_id')
        ->where('appointment_states_id', 2)
        ->orWhere('appointment_states_id', 3)
        ->orWhere('appointment_states_id', 4)
        ->union($solicitadas)
        ->get(); */

        $solicitadas = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->select('estimated_start_date AS start',
                'estimated_end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color AS className',
                'appointment_states_id',
                'format_id')
        ->where('appointment_states_id', 1);

        if($id){
            $solicitadas = $solicitadas->where('inspector_id', $id);
        }

        // $result = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
        $result = InspectionAppointment::
            join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->select('start_date AS start',
                'end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color',
                'appointment_states_id',
                'format_id')
        ->union($solicitadas);

        if($id){
            $result = $result->where('inspectors.id', $id);
        }

        $result = $result->where('appointment_states_id', 2)  
            ->orWhere('appointment_states_id', 3)
            ->orWhere('appointment_states_id', 4)->get();

        // dd($result);

        //Se agrega la hora 23:59:59 a la fecha final para que se vea el día final correcto en el calendario y un alert al className para los colores de los eventos
        foreach($result as $item){
            $item->end = $item->end.'T23:59:59';
        }
        
        echo json_encode($result);      
    }

    public function complete(Request $request, $id)
    {

        $request->validate([
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d',
        ]);

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }
        else
        {

            $appointment = InspectionAppointment::find($id);

            //Se valida si es una cita con estado solicitada
            if($appointment->appointment_states_id == 1){

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
                            'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasAdded'),
                        ]); */

                        $appointment->update([
                            'assignment_date'       => date('Y-m-d H:i:s'),
                            'start_date'            => $request['start_date'],
                            'end_date'              => $request['end_date'],
                            'appointment_states_id' => 2,
                        ]);

                        $menssage = \Lang::get('validation.MessageCreated');
                        echo json_encode([
                            'status' => $menssage,
                        ]);
                    
                    }
                    
                }
            }else{
                $menssage = \Lang::get('validation.MessageError');
                echo json_encode([
                    'error' => $menssage,
                ]);
            }
        }
    }

    public function format(Request $request, $id)
    {

        /* echo json_encode([
            'status' => Preformato::find($request->preformat_id)->format,
        ]); */
        $cita = InspectionAppointment::findOrFail($id);

        if($cita->appointment_states_id == 2)
        {

            $format = new Format();
            $format->company_id = $request->company_id;
            $format->client_id = $request->client_id;
            $format->preformat_id = $request->preformat_id;
            $format->format = Preformato::find($request->preformat_id)->format;
            $format->state = 1;


            if ($format->save())
            {
                $cita->format_id = $format->id;

                if($cita->save())
                {

                    Log::info('Id de cita: '.$format->id);
                    echo json_encode([
                        'status' => trans_choice('words.Format',1).' '.trans('words.HasAdded'),
                    ]);
                }
            }
            else
            {
                echo json_encode([
                    'error' => trans('words.UnableCreate').' '.trans_choice('words.Format',1),
                ]);
            }
        }
        else
        {
            $menssage = \Lang::get('validation.MessageError');
            echo json_encode([
                'error' => $menssage,
            ]);
        }
    }
}
