<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\GeneralController;

class InspectorAgenda extends Model
{

    protected $fillable = ['inspector_id', 'start_date', 'end_date', 'city_id', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    public function city()
    {
        return $this->belongsTo(Citie::class);
    }

    /**
     * Muestra las agendas que puedan atender a una cita
     *
     */
    public static function obtenerAgendasDisponibles($cita)
    {
        $subtype_id = $cita->inspection_subtype_id;
        $company_id = $cita->contract->company_id;
        $rangoCita = collect();

        // Obtener un arreglo de días de la cita a consultar
        for($i=$cita->estimated_start_date ; $i<=$cita->estimated_end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $rangoCita->push($i);
        }
        
        $agendas = InspectorAgenda::whereHas('inspector.inspectorType', function($q) use($subtype_id){
                $q->where('inspection_subtypes_id', '=', $subtype_id);
            })
            ->whereHas('inspector.companies', function($q) use($company_id){
                $q->where('companies.id', $company_id);
            })
            ->where('start_date', '<=', $cita->estimated_start_date)
            ->where('end_date', '>=', $cita->estimated_end_date)
        ->get();

        $citas = InspectionAppointment::
            whereIn('appointment_states_id', [2,3,4])
            ->whereHas('inspectionSubtype', function($q) use($subtype_id){
                $q->where('id', '=', $subtype_id);
            })            
            ->whereHas('contract.company', function($q) use($company_id){
                $q->where('id', $company_id);
            })
            ->select('inspector_id', 'start_date', 'end_date')
        ->get();

        $agendasDisponibles = collect();
        
        foreach($agendas as $agenda){

            $fechasCitas = [];

            // Consultar las citas que se encuentren dentro del rango y coinicidan con el inspector de la agenda
            $citasConsultadas = $citas->where('inspector_id', $agenda->inspector_id)
                ->where('start_date', '>=', $agenda->start_date)
                ->where('end_date', '<=', $agenda->end_date);

            // Si la agenda no tiene citas asignadas, añada esa agenda al arreglo de respuesta
            if ( $citasConsultadas->isEmpty() ){
                $agendasDisponibles->push($agenda);
            }else{
                /* for($i=$agenda->start_date ; $i<=$agenda->end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                    $fechasAgendas[] = $i;
                } */
                $fechasAgendas = collect( GeneralController::getDaysArray($agenda->start_date, $agenda->end_date) );
                

                // dd( $citasConsultadas );
                foreach($citasConsultadas as $cita){
                    /* for($i=$cita->start_date ; $i<=$cita->end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                        $fechasCitas[] = $i;
                    } */
                    $fechasCitas = array_merge( GeneralController::getDaysArray($cita->start_date, $cita->end_date), $fechasCitas );
                }


                // dd( $fechasCitas );

                $diasDisponibles = $fechasAgendas->diff($fechasCitas);

                // Comparar si los días de la cita a consultar estan disponibles
                if( $rangoCita->intersect($diasDisponibles) == $rangoCita ){
                    $agendasDisponibles->push($agenda);
                }
            }
        }

        return ($agendasDisponibles);
    }

    /**
     * Retorna las agendas con la distancia entre la ciudad de la agenda comparada con la sede de la cita
     *
     */
    public static function obtenerAgendasOrdenadasDistanciaParaCita($cita)
    {
        $responseProceso = ['success' => true, 'response' => collect(), 'message' => ''];

        $agendas = self::obtenerAgendasDisponibles($cita);

        $fechasCitas = collect();
            
        for($i=$cita->estimated_start_date ; $i<=$cita->estimated_end_date ; $i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $fechasCitas->push($i);
        }

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> false,
                "verify_peer_name"=> false,
            ),
        );
        
        // Llamar a la api de geocoding de google maps para obtener las coordenadas de las ciudades
        $jsonGeocoding = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$agendas->pluck('city.name').'&key=AIzaSyBUCXlkrB9E1qZYjF3j5OZxXoeD9gYGRPs',false,stream_context_create($arrContextOptions));
        $agendasLocation = json_decode( $jsonGeocoding, true );
        
        if( $agendasLocation['status'] != 'OK' ){
            $responseProceso['success'] = false;
            $responseProceso['message'] = trans('words.ErrorGeocoding');
        }

        // dd( $agendasLocation );
        // dd( $agendasLocation['results'][1]['geometry']['location'] );

        // Obtener la distancia en millas entre la cita consultada a la ubicación de la agenda
        $agendas->each(function($item, $key) use($agendasLocation, $cita){
            $item->distance = ((ACOS(SIN($cita->headquarters->latitude * PI() / 180) * SIN($agendasLocation['results'][$key]['geometry']['location']['lat'] * PI() / 180) + COS($cita->headquarters->latitude * PI() / 180) * COS($agendasLocation['results'][$key]['geometry']['location']['lat'] * PI() / 180) * COS(($cita->headquarters->longitude - $agendasLocation['results'][$key]['geometry']['location']['lng']) * PI() / 180)) * 180 / PI()) * 60 * 1.1515);
        });

        // Ordenar las agendas por distancia (mostrar las más cercanas primero)
        $agendasOrdenadas = $agendas->sortBy('distance');

        if($agendasOrdenadas->isNotEmpty()){
            $responseProceso['response'] = $agendasOrdenadas;
        }

        return $responseProceso;
        return $agendasOrdenadas;
    }
}
