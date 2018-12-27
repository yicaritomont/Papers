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
use App\InspectionSubtype;
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
        if(auth()->user()->hasRole('Inspector')){
            $request['id'] = auth()->user()->inspectors->id;
        }
        
        $appointment_states = AppointmentState::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])->get();

        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');

        // Si se consultan las citas de un inspector (Usuario inspector)
        if($request->get('id')){

            $inspector = Inspector::where('id', $request->get('id'))->with('inspection_appointments', 'user', 'inspectorType')->firstOrFail();

            if(count($inspector->inspection_appointments) == 0)
            {
                Session::flash('alert', ['info', trans('words.AppointmentEmpty')]);
            }

            $this->authorize('validateId', $inspector);

            return view('inspection_appointment.index',compact('inspector', 'appointment_states', 'companies', 'appointment_locations'));

        }

        $subtypes = InspectionSubtype::with('inspection_types')->get()->pluck('subtype_type', 'id');
        
        if( auth()->user()->hasRole('Cliente') )
        {
            $contracts = Contract::where('client_id', auth()->user()->clients->id)->where('status', 1)->get()->pluck('name', 'id');

            $clientAuth = auth()->user()->clients->id;
            return view('inspection_appointment.index',compact('contracts', 'subtypes', 'clientAuth', 'appointment_states', 'appointment_locations'));
        }
        
        
        if( !auth()->user()->hasRole('Admin') )
        {
            $company = Company::findOrFail(session()->get('Session_Company'));

            $companySlug = $company->slug;

            $inspectors = Inspector::with('user.companies')->whereHas('user.companies', function($q) use($companySlug){
                $q->where('slug', '=', $companySlug);
            })->get()->pluck('user.name', 'id');

            $clients = Client::with('user.companies')->whereHas('user.companies', function($q) use($companySlug){
                $q->where('slug', '=', $companySlug);
            })->get()->pluck('user.name', 'id');

            return view('inspection_appointment.index', compact('subtypes', 'company', 'inspectors', 'appointment_states', 'appointment_locations', 'clients'));
        }

        // Administrador

        return view('inspection_appointment.index',compact('subtypes', 'inspectors', 'appointment_states', 'appointment_locations', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
    public function store(Request $request)
    {
        if( !auth()->user()->hasRole('Admin') ){
            $request['company_id'] = session()->get('Session_Company');
        }
        
        if( auth()->user()->hasRole('Cliente') ){
            $request['client_id'] = auth()->user()->clients->id;
        }

        $request->validate([
            'headquarters_id'           => 'required',
            'contract_id'               => 'required',
            'client_id'                 => 'required',
            'estimated_start_date'      => 'required|date|date_format:Y-m-d',
            'estimated_end_date'        => 'required|date|date_format:Y-m-d',
        ]);

        // Validación de campos ocultos
        if( !isset($request->company_id) )
        {
            echo json_encode([
                'error' => trans('words.Select').' '.trans_choice('words.Company', 1),
            ]);
        }
        elseif( !isset($request->inspection_subtype_id) )
        {
            echo json_encode([
                'error' => trans('words.Select').' '.trans_choice('words.InspectionSubtype', 1),
            ]);
        }

        // Si selecciono un cliente que no pertenece a la compañia
        if( !Company::getCompanyClientsById($request->company_id)->pluck('id')->contains($request->client_id) )
        {
            abort(403, 'This action is unauthorized.');
        }
        // Si selecciono un contrato que no pertenece al cliente
        elseif( !Client::getClientContractsById($request->client_id)->pluck('id')->contains($request->contract_id) )
        {
            abort(403, 'This action is unauthorized.');    
        }
        // Si selecciono una sede que no pertenece al cliente
        elseif( !Client::getClientHeadquartersById($request->client_id)->pluck('id')->contains($request->headquarters_id) )
        {
            abort(403, 'This action is unauthorized.');    
        }

        // Validar si la fecha de inicio ingresada supera a la fecha final
        elseif($request->estimated_start_date > $request->estimated_end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }

        // Validar si insgreso una fecha anterior a la actual
        elseif($request->estimated_start_date < date('Y-m-d')){
            echo json_encode([
                'error' => trans('words.DateGreater'),
            ]);
        }

        else
        {
            $fechasCitas = collect( GeneralController::getDaysArray($request->estimated_start_date, $request->estimated_end_date) );

            $requestParam = new Request;
            $requestParam->subtype_id = $request->inspection_subtype_id;
            $requestParam->company_id = $request->company_id;

            // Traer todos los días disponibles de los inspectores filtrados por una compañía y un subtipo de inspección
            $agenasDisponibles = json_decode(InspectorAgendaController::subtype($requestParam), true);

            // Validar si seleccionó un subtipo
            if(isset($agenasDisponibles['agendas'])){

                // Validar si las fechas seleccionadas estan habilidas
                if($fechasCitas->intersect($agenasDisponibles['agendas']) == $fechasCitas){
                    InspectionAppointment::create([
                        'inspection_subtype_id'     => $request['inspection_subtype_id'],
                        'headquarters_id'           => $request['headquarters_id'],
                        'contract_id'               => $request['contract_id'],
                        'client_id'                 => $request['client_id'],
                        'request_date'              => date('Y-m-d H:i:s'),
                        'estimated_start_date'      => $request['estimated_start_date'],
                        'estimated_end_date'        => $request['estimated_end_date'],
                    ]);
                    
                    echo json_encode([
                        'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasAdded'),
                    ]);
                }else{
                    echo json_encode([
                        'error' => trans('words.IncorrectDate'),
                    ]);
                }
            }else{
                echo json_encode([
                    'error' => trans('words.IncorrectDate'),
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
        $cita = InspectionAppointment::with('inspectionSubtype.inspection_types:id,name', 'client.user:id,name', 'contract.company', 'inspector.user:id,name', 'headquarters:id,name')
            ->where('inspection_appointments.id', $id)
        ->first();
        // dd($cita);
        
        if(auth()->user()->hasRole('Cliente')){
            // Validar si selecciono la cita de otro cliente
            if( $cita->client_id != auth()->user()->clients->id ){
                abort(403, 'This action is unauthorized.');
            }
        }elseif(auth()->user()->hasRole('Inspector')){
            // Validar si esta seleccionando la cita de otro inspector
            $this->authorize('validateId', $cita->inspector);
        }
        
        // Si selecciono una cita que no pertenece a la compañia en sesión
        if( !CompanyController::compareCompanySession([$cita->contract->company]) ){
            abort(403, 'This action is unauthorized.');
        }
        
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
        $inspection_appointment = InspectionAppointment::with('inspectionSubtype')->find($id);

        // Si selecciono una cita que no pertenece a la compañia en sesión
        if( !CompanyController::compareCompanySession([$inspection_appointment->contract->company]) ){
            abort(403, 'This action is unauthorized.');
        }

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
            'start_date'    => 'required|date|date_format:Y-m-d',
            'end_date'      => 'required|date|date_format:Y-m-d',
        ]);

        $inspection_appointment = InspectionAppointment::findOrFail($id);
        // dd( $inspection_appointment->inspector_id );
        // Validar si se modifico el id del formulario
        if( !CompanyController::compareCompanySession([$inspection_appointment->contract->company]) ){
            abort(403, 'This action is unauthorized.');
        }

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }

        // Validar si insgreso una fecha anterior a la actual
        elseif($request->start_date < date('Y-m-d')){
            echo json_encode([
                'error' => trans('words.DateGreater'),
            ]);
        }

        // Validar si insgreso una fecha anterior a la actual
        elseif($request->start_date < date('Y-m-d')){
            echo json_encode([
                'error' => trans('words.DateGreater'),
            ]);
        }

        else
        {
            
            
            //Se valida si es una cita con estado activa
            if($inspection_appointment->appointment_states_id == 2){
        
                //Contadores de error para las validaciones
                $contFecha=0;
                $contCitasError=0;

                // Se Consula las agendas filtrada por un inspector
                $inspectorAgenda = InspectorAgenda::where('inspector_id', '=', $inspection_appointment->inspector_id)->get();

                foreach($inspectorAgenda as $agenda)
                {

                    //Si el rango de dias ingresado esta dentro de la agenda
                    if($request->input('start_date') >= $agenda->start_date && $request->input('end_date') <= $agenda->end_date )
                    {
                        $contFecha++;

                        //Consulte todas las citas por el inspector y fechas seleccionadas, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                        $citas = InspectionAppointment::where([
                            ['inspector_id', '=', $inspection_appointment->inspector_id],
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
                {dd( $contFecha );
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

                        if(($request['start_date'] != $inspection_appointment->start_date) || ($request['end_date'] != $inspection_appointment->end_date)){

                            //Cambie el estado de la cita a reprogramado
                            $inspection_appointment->appointment_states_id = 5;

                            $inspection_appointment->save();

                            InspectionAppointment::create([
                                'inspector_id'              => $inspection_appointment->inspector_id,
                                'appointment_states_id'     => 2,
                                'headquarters_id'           => $inspection_appointment->headquarters_id,
                                'inspection_subtype_id'     => $inspection_appointment->inspection_subtype_id,
                                'contract_id'               => $inspection_appointment->contract_id,
                                'client_id'                 => $inspection_appointment->client_id,
                                'format_id'                 => $inspection_appointment->format_id,
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

        // Si selecciono una cita que no pertenece a la compañia en sesión
        if( !CompanyController::compareCompanySession([$inspection_appointment->contract->company]) ){
            abort(403, 'This action is unauthorized.');
        }

        $inspection_appointment->appointment_states_id = 6;
        $inspection_appointment->save();

        echo json_encode([
            'status' => trans_choice('words.Inspectionappointment', 1).' '.trans('words.HasEliminated'),
        ]);
    }

    /**
     * Muestra los resultados de la tabla citas para mostrarlos en el calendario
     *
     * @return JSON
     */
    public function events($type=null, $id=null)
    {
        $solicitadas = InspectionAppointment::
            join('contracts', 'contracts.id', '=', 'inspection_appointments.contract_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('clients', 'clients.id', '=', 'inspection_appointments.client_id')
            ->join('users', 'users.id', '=', 'clients.user_id')
            ->leftJoin('formats', 'formats.id', '=', 'inspection_appointments.format_id')
            ->select('estimated_start_date AS start',
                'estimated_end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color AS className',
                'appointment_states_id',
                'format_id',
                'inspection_appointments.client_id',
                'inspection_subtype_id',
                'contracts.company_id',
                'formats.status AS format_status')
        ->where('appointment_states_id', 1);

        if($type == 'inspector'){
            $solicitadas = $solicitadas->where('inspector_id', $id);
        }elseif($type == 'company'){
            $solicitadas = $solicitadas->whereHas('contract.company', function($q) use($id){
                $q->where('id', '=', $id);
            });
        }elseif($type == 'client'){
            $solicitadas = $solicitadas->where('client_id', $id);
        }

        $result = InspectionAppointment::
            join('contracts', 'contracts.id', '=', 'inspection_appointments.contract_id')
            ->join('inspectors', 'inspectors.id', '=', 'inspection_appointments.inspector_id')
            ->join('appointment_states', 'appointment_states.id', '=', 'inspection_appointments.appointment_states_id')    
            ->join('users', 'users.id', '=', 'inspectors.user_id')
            ->leftJoin('formats', 'formats.id', '=', 'inspection_appointments.format_id')
            ->select('start_date AS start',
                'end_date AS end',
                'users.name AS title',
                'inspector_id',
                'inspection_appointments.id',
                'appointment_states.color',
                'appointment_states_id',
                'format_id',
                'inspection_appointments.client_id',
                'inspection_subtype_id',
                'contracts.company_id',
                'formats.status AS format_status')
            ->where(function($q){
                $q->where('appointment_states_id', 2)  
                    ->orWhere('appointment_states_id', 3)
                    ->orWhere('appointment_states_id', 4);
            })
        ->union($solicitadas);

        if($type == 'inspector'){
            $result = $result->where('inspectors.id', $id);
        }elseif($type == 'company'){
            $result = $result->whereHas('contract.company', function($q) use($id){
                $q->where('id', '=', $id);
            });
        }elseif($type == 'client'){
            $result = $result->where('client_id', $id);
        }

        $preformato = Preformato::select('inspection_subtype_id', 'company_id')->get();

        $result = $result->get();

        $result->each(function($cita, $key) use($preformato){

            // Se agrega un nuevo campo hasPreformat para comprobar si la cita tiene un preformato
            if( $preformato->where('inspection_subtype_id', $cita->inspection_subtype_id)->where('company_id', $cita->company_id)->isNotEmpty() ){
                $cita->hasPreformat = 1;
            }else{
                $cita->hasPreformat = 0;
            }

            //Se agrega la hora 23:59:59 a la fecha final para que se vea el día final correcto en el calendario
            $cita->end = $cita->end.'T23:59:59';
        });
        
        echo json_encode($result);      
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d',
            'inspector_id' => 'required',
        ]);

        $appointment = InspectionAppointment::find($id);

        // Si selecciono una cita que no es de la compañía en sesión
        if( !CompanyController::compareCompanySession([$appointment->contract->company]) ){
            abort(403, 'This action is unauthorized.');
        }

        // Si selecciono un inspector que no pertenece a la compañia
        if( !Company::getCompanyInspectorsById($appointment->contract->company->id)->pluck('id')->contains($request->inspector_id) )
        {
            abort(403, 'This action is unauthorized.');
        }

        // Validar si la fecha de inicio ingresada supera a la fecha final
        if($request->start_date >$request->end_date)
        {
            echo json_encode([
                'error' => trans('words.ErrorRangeDate'),
            ]);
        }

        // Validar si insgreso una fecha anterior a la actual
        elseif($request->start_date < date('Y-m-d')){
            echo json_encode([
                'error' => trans('words.DateGreater'),
            ]);
        }

        else
        {
            //Se valida si es una cita con estado solicitada
            if($appointment->appointment_states_id == 1){

                //Contadores de error para las validaciones
                $contFecha=0;
                $contCitasError=0;

                // Se Consula las agendas filtrada por un inspector
                $inspectorAgenda = InspectorAgenda::where('inspector_id','=',$request->inspector_id)->get();

                foreach($inspectorAgenda as $agenda)
                {

                    //Si el rango de dias ingresado esta dentro de la agenda
                    if($request['start_date'] >= $agenda->start_date && $request['end_date'] <= $agenda->end_date )
                    {
                        $contFecha++;

                        //Consulte todas las citas por el inspector y fechas seleccionadas, se exceptuan las citas reprogramadas (5) y/o canceladas (6)
                        $citas = InspectionAppointment::where([
                            ['inspector_id', '=', $request->inspector_id],
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
                        dd('Llego');
                        $appointment->update([
                            'assignment_date'       => date('Y-m-d H:i:s'),
                            'start_date'            => $request['start_date'],
                            'end_date'              => $request['end_date'],
                            'inspector_id'          => $request['inspector_id'],
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
