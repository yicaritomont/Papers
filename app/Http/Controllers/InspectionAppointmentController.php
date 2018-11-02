<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionAppointment;
use App\Inspector;
use App\AppointmentState;
use App\InspectionType; 
use App\AppointmentLocation;
use App\InspectorAgenda;
use DB;
use View;
use App\Http\Requests\InspectionAppointmentRequest;

class InspectionAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = InspectionAppointment::latest()->with(['inspector', 'appointmentState', 'inspectionSubtype'])->paginate();
        $inspectors = Inspector::pluck('name', 'id');
        $appointment_states = AppointmentState::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');
        return view('inspection_appointment.index',compact('result', 'inspectors', 'appointment_states', 'appointment_locations', 'inspection_types'));
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
        $contHoras=0;
        $contCitasError=0;

        // Se Consula las agendas filtrada por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $agenda){

            // Si el día de la cita hay agendas de ese inspector
            if($request->input('date') == $agenda->date){
                $contFecha++;

                //Si las horas ingresadas estan en el rango de la agenda
                if($request->input('start_time') >= $agenda->start_time && $request->input('end_time') <= $agenda->end_time ){
                    $contHoras++;

                    //Consulte todas las citas por el inspector y fecha seleccionados, se exceptuan las citas reprogramadas y/o canceladas
                    $citas = InspectionAppointment::where([
                                                ['inspector_id', '=', $request->input('inspector_id')],
                                                ['date', '=', $request->input('date')],
                                                ['appointment_states_id', '!=', 6],
                                                ['appointment_states_id', '!=', 2],
                                            ])->get();
                    
                    foreach($citas as $cita){
                        if(($request->input('end_time') < $cita->end_time && $request->input('start_time') < $cita->start_time && $request->input('end_time') < $cita->start_time) || ($request->input('end_time') > $cita->end_time && $request->input('start_time') > $cita->start_time && $request->input('start_time') > $cita->end_time)){
                            //Se comprueba si las horas ingresadas no se crucen con otra cita el mismo día
                        }else{
                            $contCitasError++;
                        }
                    }
                }
            }
           
        }

        //Comprueba si selecciono un día incorrecto
        if($contFecha==0){
            echo json_encode([
                'error' => trans('words.IncorrectDate'),
            ]);
        }else{

            //Comprueba si las horas de la cita no concuerda con el de la agenda
            if($contHoras==0){
                echo json_encode([
                    'error' => trans('words.IncorrectHours'),
                ]);
            }else{

                //Comprueba si las horas de la cita ingresada ya esta ocupada por otra cita
                if($contCitasError>0){
                    echo json_encode([
                        'error' => trans('words.IncorrectAppointments'),
                    ]);
                }else{

                    if (InspectionAppointment::create($request->except(['_token', 'dateAgenda', 'startTimeAgenda', 'endTimeAgenda', 'inspection_type_id']))) {
                        echo json_encode([
                            'status' => trans('words.Inspectionappointment').' '.trans('words.HasAdded'),
                        ]);
                    } else {
                        echo json_encode([
                            'error' => trans('words.UnableCreate').' '.trans('words.Inspectionappointment'),
                        ]);
                    }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspection_appointment = InspectionAppointment::find($id);
        $inspectors = Inspector::pluck('name', 'id');
        $inspection_types = InspectionType::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $appointment_states  = AppointmentState::pluck('name', 'id');

        return view('inspection_appointment.edit',compact('inspection_appointment', 'inspectors', 'inspection_types', 'appointment_locations', 'appointment_states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InspectionAppointmentRequest $request, $id)
    {
        
        //Contadores de error para las validaciones
        $contFecha=0;
        $contHoras=0;
        $contCitasError=0;

        // Se Consula las agendas filtrada por un inspector
        $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$request->input('inspector_id'))->get();

        foreach($inspectorAgenda as $agenda){

            // Si el día de la cita hay agendas de ese inspector
            if($request->input('date') == $agenda->date){
                $contFecha++;

                //Si las horas ingresadas estan en el rango de la agenda
                if($request->input('start_time') >= $agenda->start_time && $request->input('end_time') <= $agenda->end_time ){
                    $contHoras++;

                    //Consulte todas las citas por el inspector y fecha seleccionados, se exceptua la cita que se esta editando y las citas reprogramadas y/o canceladas
                    $citas = InspectionAppointment::where([
                                                ['inspector_id', '=', $request->input('inspector_id')],
                                                ['date', '=', $request->input('date')],
                                                ['id', '!=', $id],
                                                ['appointment_states_id', '!=', 6],
                                                ['appointment_states_id', '!=', 2],
                                            ])->get();
                    
                    foreach($citas as $cita){
                        if(($request->input('end_time') < $cita->end_time && $request->input('start_time') < $cita->start_time && $request->input('end_time') < $cita->start_time) || ($request->input('end_time') > $cita->end_time && $request->input('start_time') > $cita->start_time && $request->input('start_time') > $cita->end_time)){
                            //Se comprueba si las horas ingresadas no se crucen con otra cita el mismo día
                        }else{
                            $contCitasError++;
                        }
                    }
                }
            }
           
        }

        //Comprueba si selecciono un día incorrecto
        if($contFecha==0){
            echo json_encode([
                'error' => trans('words.IncorrectDate'),
            ]);
        }else{

            //Comprueba si las horas de la cita no concuerda con el de la agenda
            if($contHoras==0){
                echo json_encode([
                    'error' => trans('words.IncorrectHours'),
                ]);
            }else{

                //Comprueba si las horas de la cita ingresada ya esta ocupada por otra cita
                if($contCitasError>0){
                    echo json_encode([
                        'error' => trans('words.IncorrectAppointments'),
                    ]);
                }else{

                    $inspection_appointment = InspectionAppointment::findOrFail($id);

                    if(($request->date != $inspection_appointment->date) || ($request->start_time != $inspection_appointment->start_time) || ($request->end_time != $inspection_appointment->end_time)){
                        
                        $inspection_appointment->appointment_states_id = 6;
                        $inspection_appointment->save();
                        
                        if (InspectionAppointment::create($request->except('permissions', 'inspection_type_id'))) {
                            echo json_encode([
                                'status' => trans('words.Inspectionappointment').' '.trans('words.HasReprogrammed'),
                            ]);
                        } else {
                            echo json_encode([
                                'error' => trans('words.UnableUpdated').' '.trans('words.Inspectionappointment'),
                            ]);
                        }
                    }else{
                        $inspection_appointment->fill($request->except('permissions', 'inspection_type_id'));
                        $inspection_appointment->save();
                        echo json_encode([
                            'status' => trans('words.Inspectionappointment').' '.trans('words.HasUpdated'),
                        ]);
                        
                    }

                    
                    
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
        $inspection_appointment->appointment_states_id = 2;
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
    public function events(){
        // echo "XD";
        // $result = InspectorAgenda::select(DB::raw('CONCAT(date,"T",start_time) AS start, CONCAT(date,"T",end_time) AS end, slug'))->get();
        
        $result = InspectionAppointment::join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
                ->join('inspection_subtypes', 'inspection_subtypes.id', '=', 'inspection_appointments.inspection_subtype_id')
                ->join('inspection_types', 'inspection_types.id', '=', 'inspection_subtypes.inspection_type_id')
                ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')
                ->select(DB::raw('CONCAT(date,"T",start_time) AS start,
                                 CONCAT(date,"T",end_time) AS end,
                                 inspection_appointments.id,
                                 inspection_types.name AS type,
                                 inspection_subtypes.name AS subType,
                                 inspectors.name AS inspector,
                                 CONCAT("alert-",appointment_states.color) AS className,
                                 inspector_id,
                                 inspection_subtype_id,
                                 inspection_subtypes.inspection_type_id,
                                 appointment_location_id,
                                 appointment_states_id'))
                ->where([
                    ['appointment_states_id', '!=', 6],
                    ['appointment_states_id', '!=', 2],
                ])->get();
        
        echo json_encode($result);
        // echo $result;
       
    }

    /**
     * Muestra los subtipos de un tipo dado
     *
     * @return JSON
     */
    public function subtypes(Request $request){
        
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
}
