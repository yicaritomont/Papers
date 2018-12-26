<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionAppointment extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'format_id', 'inspector_id', 'inspection_subtype_id', 'appointment_location_id', 'appointment_states_id',  'contract_id', 'client_id', 'request_date', 'estimated_start_date', 'estimated_end_date', 'assignment_date', 'start_date', 'end_date'
    ];

    public function inspector()
    {
        return $this->belongsTo('App\Inspector', 'inspector_id', 'id');
    }

    public function appointmentState()
    {
        return $this->belongsTo('App\AppointmentState', 'appointment_states_id', 'id');
    }

    public function appointmentLocation()
    {
        return $this->belongsTo('App\AppointmentLocation', 'appointment_location_id', 'id');
    }

    public function inspectionSubtype()
    {
        return $this->belongsTo('App\InspectionSubtype', 'inspection_subtype_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract', 'contract_id', 'id');
    }

    public function formats()
    {
        return $this->belongsTo('App\Format', 'format_id', 'id');
    }
}
