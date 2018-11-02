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
        'inspector_id', 'inspection_subtype_id', 'appointment_location_id', 'appointment_states_id',  'date', 'start_time', 'end_time'
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
}
