<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionAppointment;
use App\Inspector;
use App\AppointmentState;
use App\InspectionType; 
use App\AppointmentLocation;
use View;

class InspectionAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = InspectionAppointment::latest()->with(['inspector', 'appointmentState', 'inspectionType'])->paginate();
        return view('inspection_appointment.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspectors = Inspector::pluck('name', 'id');
        $appointment_states = AppointmentState::pluck('name', 'id');
        $appointment_locations = AppointmentLocation::pluck('coordenada','id');
        $inspection_types = InspectionType::pluck('name', 'id');

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
        if (InspectionAppointment::create($request->except('_token'))) {
            flash(trans('words.Inspectionappointment').' '.trans('words.HasAdded'));
        } else {
             flash()->error(trans('words.UnableCreate').' '.trans('words.Inspectionappointment'));
        }
        return redirect()->route('inspectionappointments.index');
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
    public function update(Request $request, $id)
    {
        $inspection_appointment = InspectionAppointment::findOrFail($id);
        $inspection_appointment->fill($request->except('permissions'));
        $inspection_appointment->save();
        flash()->success(trans('words.Inspectionappointment').' '.trans('words.HasUpdated'));
        return redirect()->route('inspectionappointments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
