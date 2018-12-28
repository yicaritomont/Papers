<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Helpers\Equivalencia;
use App\Format;
use App\Preformato;
use App\Company;
use App\Client;
use App\Contract;
use App\User;
use App\Estilo;
use App\File;
use App\InspectionAppointment;
use App\SignaFormat;
use App\Route;
use PDF;
use DB;
use App\Http\Controllers\Config;

class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('Inspector')){
            $inspectorId = auth()->user()->inspectors->id;
            return view('format.index', compact('inspectorId'));
        }

        return view('format.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create(Request $request)
    {
        $formato = Preformato::where('id',1)->first();
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $company = Company::where('id',session()->get('Session_Company'))->first();
        $companyselect ='none';
        $mostrar_formato = 'none';
        $disabled = '';
        if($company == '')
        {
            $company = new Company();
            $company->name = 'Administracion Principal';
            $companyselect = 'block';
            $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                            ->select('clients.id AS id', 'users.name AS name')
                            ->get()
                            ->pluck('name', 'id');
        } else {
            $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->join('user_company','user_company.user_id','=','users.id')
                        ->join('companies','companies.id','=','user_company.company_id')
                        ->where('companies.id',session()->get('Session_Company'))
                        ->select('clients.id AS id', 'users.name AS name')
                        ->get()
                        ->pluck('name', 'id');
        }
        $preformats = Preformato::pluck('name', 'id');

        if($request->get('appointment')){
            $appointment = $request->get('appointment');
            return view('format.new', compact('format', 'formato','clients','companies','companyselect','mostrar_formato','disabled','preformats', 'appointment'));
        }
        return view('format.new', compact('format', 'formato','clients','companies','companyselect','mostrar_formato','disabled','preformats'));
    }*/

    public function create(Request $request)
    {
        $cita = InspectionAppointment::with('contract.company.user:id,name', 'client.user:id,name')->find($request->get('appointment'));

        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->inspectors->id != $cita->inspector_id ){
                $alert = ['error', 'This action is unauthorized.'];
                return redirect()->route('formats.index')->with('alert',$alert);
            }
        }
        $preformato = Preformato::where([
            ['inspection_subtype_id', $cita->inspection_subtype_id],
            ['company_id', $cita->contract->company_id],
        ])->first();

        $companyName = $cita->contract->company->user->name;
        $clientName = $cita->client->user->name;
        $preformatoName = $preformato->name;

        // return $cita;
        // return $preformato->name;
        $formato = $this->llenarCabeceraFormato($cita->client_id, $cita->contract->company_id, $preformato->id);

        $datos = [
            'company'   => $formato['company'],
            'client'    => $formato['client'],
            'contract'  => $formato['contract'],
            'project'   => ['name' => 'Proyecto Prueba'],
            'page'      => ['num' => '', 'tot' => ''],
        ];

        $formatoSeteado = $this->reemplazarInformacionFormato($datos, $formato['preformato']['header'].$formato['preformato']['format']);
        
        // return ($formatoSeteado);

        $mostrar_formato = 'block';
        /* $companyselect ='none';
        $formato = Preformato::where('id',1)->first();
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $company = Company::where('id',session()->get('Session_Company'))->first();
        $disabled = '';
        if($company == '')
        {
            $companyselect = 'block';
            $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                            ->select('clients.id AS id', 'users.name AS name')
                            ->get()
                            ->pluck('name', 'id');
        } else {
            $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->join('user_company','user_company.user_id','=','users.id')
                        ->join('companies','companies.id','=','user_company.company_id')
                        ->where('companies.id',session()->get('Session_Company'))
                        ->select('clients.id AS id', 'users.name AS name')
                        ->get()
                        ->pluck('name', 'id');
        }
        $preformats = Preformato::pluck('name', 'id'); */

        if($request->get('appointment'))
        {
            $appointment = $request->get('appointment');
            return view('format.new', compact('formatoSeteado', 'mostrar_formato', 'appointment', 'companyName', 'clientName', 'preformatoName'));
        }
        else
        {
            return redirect()->route('formats.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'appointment'   => 'required',
        ]);
        /* if($request->company_id == null)
        {
          $request->company_id = session()->get('Session_Company');
        } */
        $cita = InspectionAppointment::with('contract')->find($request->appointment);

        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->inspectors->id != $cita->inspector_id ){
                $alert = ['error', 'This action is unauthorized.'];
                return redirect()->route('formats.index')->with('alert',$alert);
            }
        }
        /* dd($request->appointment);
        dd($cita); */
        $preformato = Preformato::where([
            ['inspection_subtype_id', $cita->inspection_subtype_id],
            ['company_id', $cita->contract->company_id],
        ])->first();

        $format = new Format();
        $format->company_id = $cita->contract->company_id;
        $format->client_id = $cita->client_id;
        $format->preformat_id = $preformato->id;
        $format->format = $request->format_expediction;
        $format->status = 1;

        if ($format->save()) {
            $cita = InspectionAppointment::findOrFail($request->appointment);

            if($cita->appointment_states_id == 2)
            {
                $cita->format_id = $format->id;

                if($cita->save())
                {
                    $alert = ['success', trans_choice('words.Format',1).' '.trans('words.HasAdded')];
                }
            }
            else
            {
                $alert = ['success', trans('words.ErrorLinkFormat')];
            }
      } else {
          $alert = ['error',trans('words.UnableCreate').' '.trans_choice('words.Format',1)];
      }
      return redirect()->route('formats.index')->with('alert', $alert);
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
        $formato = Format::find($id);

        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->id != $formato->inspection_appointments->inspector->user_id ){
                $alert = ['error', 'This action is unauthorized.'];
                return redirect()->route('formats.index')->with('alert',$alert);
            }
        }
        
        $mostrar_formato = 'block';
        $companyselect ='block';
        $state_format = $state_firma = $disabled= '';
        if (session()->get('Session_Company') != '')
        {
          $companyselect ='none';
        }
        $formato = Format::find($id);
      
        if ($formato->status == 0)
        {
          $alert = ['success', trans('words.theFormatInactive')];
          return redirect()->route('formats.index')->with('alert',$alert);
        } 
        else 
        {
            if ($formato != '') 
            {
                if ($formato->status == 2)
                {
                    $state_format = 'none';
                    $disabled = 'disabled';
                }
            }
            $companies = Company::with('user')->get()->pluck('user.name', 'id');
            $clients = Client::with('user')->get()->pluck('user.name', 'id');
            $preformats = Preformato::pluck('name', 'id');
            
            if ($formato != '')
            {
                // Verifica la cantidad de firmas que lleva el formato
                $consultar_cantidad_firmas = SignaFormat::where('id_formato',$id)->get()->count();
                if($consultar_cantidad_firmas >=2)
                {
                    $state_firma = 'none';
                }
                if ($formato->status == 2 )
                {
                    $state_format = 'none';
                }
            }
        }
        return view('format.edit', compact('formato','companyselect','mostrar_formato','disabled','companies','clients','preformats','user','state_format','state_firma'));
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
        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->id != $formato->inspection_appointments->inspector->user_id ){
                redirect()->route('home');
            }
        }

        $formato = Format::findOrFail($id);
        $formato->format = $request->format_expediction;
        $formato->status = $request->state;
        $formato->save();

        $alert = ['success', trans_choice('words.Format',1).' '.trans('words.HasUpdated')];
        return redirect()->route('formats.index')->with('alert',$alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $format = Format::find($id);

        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->id != $format->inspection_appointments->inspector->user_id ){
                abort(403, 'This action is unauthorized.');
            }
        }

          //Valida que exista el servicio
          if($format)
          {
          switch ($format->status)
          {
            case 1 : $format->status = 0;
                   $accion = 'Desactivó';
              break;

            case 0 : $format->status = 1;
                   $accion = 'Activó';
              break;

            default : $format->status = 0;

                break;
          }

          $format->save();
              $menssage = \Lang::get('validation.MessageCreated');
              echo json_encode([
                  'status' => $menssage,
              ]);
          }else
              {
                $menssage = \Lang::get('validation.MessageError');
                  echo json_encode([
                      'status' => $menssage,
                  ]);
              }
    }

    public function getFormat($client, $company, $contract, $preformato){
        if ($_GET['select'] != '')
        {
            $format = Format::where('company_id','=',$_GET['company'])
            ->where('client_id','=',$_GET['select'])
            ->where('preformat_id','=',$_GET['preformato'])
            ->get()->first();
            
            if (!isset($format) & $format == '')
            {
                $client = Client::join('users', 'users.id', '=', 'clients.user_id')
                                ->select('clients.id AS id', 'users.name AS name')
                                ->where('clients.id',$_GET['select'])
                                ->get()
                                ->first();
                if ($_GET['company'] != '')
                {
                    $company = Company::where('id',$_GET['company'])->first();
                    $usuario = User::find($company->user_id);
                }
                else
                {
                    $company = Company::where('id',session()->get('Session_Company'))->first();
                    $usuario = User::find($company->user_id);
                }

                $usuario->image ='<img width="40%" src="'.asset($usuario->picture).'">';
                $usuario->iso ='<img width="40%" src="'.asset('images/iso.jpg').'">';
                $contract = Contract::where('company_id',$company->id)
                ->where('client_id','=',$client->id)
                ->first();
                $preformato = Preformato::where('id',$_GET['preformato'])->first();

                json_encode($response = [
                    'company' => $usuario,
                    'client' => $client,
                    'contract' => $contract,
                    'preformato' => $preformato,
                ]);

                if (!isset($contract))
                {
                    $error = trans('words.ThereNoContract');
                    json_encode($response = [
                        'error' => $error,
                    ]);
                }
            } else {
                $error = trans('words.FormatExists');
                json_encode($response = [
                    'error' => $error,
                ]);
            }

        }
        return $response;
    }

    public function llenarCabeceraFormato($select, $company, $preformato)
    {
      if ($select != '')
      {
        $format = Format::where('company_id','=',$company)
            ->where('client_id','=',$select)
            ->where('preformat_id','=',$preformato)
            ->get()->first();

          $client = Client::join('users', 'users.id', '=', 'clients.user_id')
                          ->select('clients.id AS id', 'users.name AS name')
                          ->where('clients.id',$select)
                          ->get()
                          ->first();
          if ($company != '')
          {
            $company = Company::where('id',$company)->first();
            $usuario = User::find($company->user_id);
          } else {
            $company = Company::where('id',session()->get('Session_Company'))->first();
            $usuario = User::find($company->user_id);
          }
          $usuario->image ='<img width="40%" src="'.asset($usuario->picture).'">';
          $usuario->iso ='<img width="40%" src="'.asset('images/iso.jpg').'">';
          $contract = Contract::where('company_id',$company->id)
            ->where('client_id','=',$client->id)
            ->first();
          $preformato = Preformato::where('id',$preformato)->first();

          json_encode($response = [
            'company' => $usuario,
            'client' => $client,
            'contract' => $contract,
            'preformato' => $preformato,
          ]);
          if (!isset($contract))
          {
            $error = trans('words.ThereNoContract');
            json_encode($response = [
              'error' => $error,
            ]);
          }

      }
      return $response;
    }

    public function cargarSelectClients()
    {
        if($_GET['company'] != '')
        {
          $clients = Client::join('users', 'users.id', '=', 'clients.user_id')
                        ->join('user_company','user_company.user_id','=','users.id')
                        ->join('companies','companies.id','=','user_company.company_id')
                        ->where('companies.id',$_GET['company'])
                        ->select('clients.id AS id', 'users.name AS name')
                        ->get()
                        ->pluck('name', 'id');
          $ChooseOption = trans('words.ChooseOption');
                    json_encode($response = [
                      'clients' => $clients,
                      'ChooseOption' => $ChooseOption]);
        }
        return $response;
    }

    public function supports($id)
    {
      $formato = Format::find($id);

        if(auth()->user()->hasRole('Inspector')){
            if ( auth()->user()->id != $formato->inspection_appointments->inspector->user_id ){
                $alert = ['error', 'This action is unauthorized.'];
                return redirect()->route('formats.index')->with('alert',$alert);
            }
        }

      if ($formato->status == 0)
      {
        $alert = ['success', trans('words.theFormatInactive')];
        return redirect()->route('formats.index')->with('alert',$alert);
      } else {
        return view('format.supports', compact('formato'));
      }
    }

    public function upload( Request $request )
    {
        $response = array(); $valid = array();
        $data = $request->all();
        //Obtenemos el id del formato
        $format_id = $request->input('formato');
        $file_id = $request->input('file_id');
        $files = $request->file('input-supports');
        //Directorio destino
        $destinationPath = "uploads/".$format_id."/";
        //Validación datos
        if( !is_null($files) ){
            foreach( $files AS $key => $item ){
                if( $item->isValid() ){
                    $indice = isset($data['file_id']) ? $data['file_id'] : $key;
                    if( $this->isValidName($indice,$data) ){
                        //Verificamos que el peso no supere el limite
                        if( $this->getValidSize($item) ){
                            //Verificamos que la extensión este permitida
                            if( $this->getValidExt($item) ){
                                array_push($valid,$item);
                            }else{
                                $response = $this->addError($response,'not_ext_valid',$item);
                            }
                        }else{
                            $response = $this->addError($response,'exceeded_weight',$item);
                        }
                    }else{
                        $response = $this->addError($response,'un_error_name_file',$item);
                    }
                }else{
					$response = $this->addError($response,'file_not_valid',$item);
                }
            }
        }
        //Subimos las imagenes si no hay errores
        if( !isset($response['error']) ){
            foreach( $valid AS $key => $item ){
                $name_url = $this->getNameFile($destinationPath,$item->getClientOriginalName());
                $upload_success = $item->move($destinationPath, $name_url['name'] );
                //Si se subio correctamente de agregar el registro
                if( $upload_success ){
                    $indice = isset($data['file_id']) ? $data['file_id'] : $key;
                    //Retiramos etiquetas PHP y HTML del nombre (XXS)
                    $nombre = strip_tags($data['name_file_'.$indice]);
                    //Generamos el objeto del archivo
                    $new_file = array
                    (
                        'mime_type'     =>  $upload_success->getMimeType(),
                        'format_id'     =>  $format_id,
                        'nombre_url'    =>  $name_url['url'],
                        'user_id'       =>  Auth::id(),
                        'extension'     =>  $upload_success->getExtension(),
                        'nombre'        =>  $nombre
                    );
                    $insert = File::insertGetId($new_file);
                    $new_file['id'] = $insert;
                    array_push($response,$new_file);
                }else{
                    $response = $this->addError($response,'not_upload',$item);
                }
            }
        }
        return response()->json($response);
    }

    public function getNameFile($path,$name)
    {
        $file = array();
        while( file_exists( public_path()."/".$path.$name ) )
        {
            $rd = rand(0,999);
            $pos = strripos($name,'.');
            $label = substr($name,0,$pos);
            $ext = substr($name,($pos+1));
            $name = $label."_".$rd.".".$ext;
        }
        $file['name'] = $name;
        $file['url'] = $path.$name;
        return $file;
    }

    public function getInitialData( Request $request )
    {
        $response = array('files' => array() , 'path' =>  $request->root() );
        $format_id = $request->input('formato');
        $response['files'] = DB::table('files')->where('format_id',$format_id)->get();
        $texts = array('txt','csv');
        foreach( $response['files'] AS $key => $item )
        {
            if( in_array($item->extension,$texts) ){
                $content = file_get_contents($item->nombre_url);
                $response['files'][$key]->content = base64_encode($content);
            }
        }
        return response()->json($response);
    }

    public function delete( Request $request  )
    {
        $id = $request->input('key');
        $support = File::find($id);
        //Eliminamos el archivo
        if( file_exists(public_path().'/'.$support->nombre_url) ){
            unlink(public_path().'/'.$support->nombre_url);
            $support->forceDelete();
        }
        return response()->json(array($id => 'delete'));
    }

    public function downloadPDF($id,$firma = "")
    {
        $format = Format::find($id);
        $estilos = Estilo::where('name','=','estilo_pdf')->first();
        $pagination = Estilo::where('name','=','paginate_pdf')->first();
        $eliminar = array('<input style="width:100%" type="text" disabled="">','<input type="text" disabled="">',
        '<textarea disabled="">','<textarea cols="80" rows="10" disabled="">','</textarea>');

        if ($format != '')
        {
            if($format->preformat_id == 1)
            {
                $format_pdf = str_replace($eliminar,'',$format->format);
            }
            else
            {
                $format_pdf = $format->format;
            }
            if ($format != '')
            {
                $format_pdf = str_replace($eliminar,'',$format->format);
                $supports = File::where('format_id','=',$format->id)->get();
                $file_pdf = '';
                foreach( $supports AS $key => $item )
                {
                    $file_pdf .= '<div class="contenedor_image"><img class="image" src="'.public_path().'/'.$item->nombre_url.'"/></di>';
                }
                $config_format = $estilos->estilos.$format_pdf.$file_pdf.$pagination->estilos;
                $pdf = \App::make('dompdf.wrapper');
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->loadHTML($config_format);

                return $pdf->stream();
                //return $pdf->output();
            }
        }
    }

    public function clearString( $string )
    {
        $clear = preg_replace("[^A-Za-z0-9]", "", $string);
        return $clear;
	}

	public function getValidSize( $file )
	{
		$size = $file->getClientSize();
		//Pasamos de Bytes a KiloBytes
		$sizeKb = ($size / 1000 );
		//verificamos si es menor igual al permitido
		if( $sizeKb <= Equivalencia::size() ){
			return true;
		}
		return false;
	}

	public function getValidExt( $file )
	{
		$types = Equivalencia::types();
		$ext = strtolower($file->getClientOriginalExtension());

		if( in_array($ext,$types) ){
			return true;
		}
		return false;
    }

    public function isValidName( $key, $data )
    {
        $base = "name_file_";
        if( isset($data[$base.$key])){
            if( !is_null($data[$base.$key]) && !empty($data[$base.$key]) ){
                return true;
            }
        }
        return false;
    }

	public function addError( $response , $message , $file )
	{
		$text = \Lang::get('words.'.$message);
		$name = $file->getClientOriginalName();

		$text = str_replace("{file}",$name,$text);

		if( isset($response['error']) ){
			$response['error'] .= "<li>".$text."</li>";
		}else{
			$response['error'] = $text;
		}
		return $response;
    }

    public function getAjaxMessage()
    {
        $words =  \Lang::get('words');
        return response()->json($words);
    }

    public function signedFormats($id)
    {
        $format = SignaFormat::where('id_formato',$id)->orderBy('created_at', 'desc')->limit(1)->first();
        $documento = HashUtilidades::generarPDFdeTXT($format->base64);
        return $documento;
    }

    public function reemplazarInformacionFormato($datos, $formato){
        $comodines = [
            'company'   => ['company_name'],
            'client'    => ['client_name'],
            'contract'  => ['contract_name', 'contract_date'],
            'project'   => ['project_name'],
            'page'      => ['page_num', 'page_tot']
        ];

        if(count($datos) > 0){
            foreach($comodines as $parent => $arrayComodines){
                foreach($arrayComodines as $string_comodin){
                    $partes = explode('_', $string_comodin);
                    $nombreAtributo = $partes[1];

                    $formato = str_replace('*'.$string_comodin.'*', $datos[$parent][$nombreAtributo], $formato);
                }
            }
        }

        return $formato;
    }
    
    public function signature($id)
    {
        $format = SignaFormat::where('id_formato',$id)->orderBy('created_at', 'desc')->limit(1)->first();
        $contents =HashUtilidades::obtenerContenidoTxt($format->base64);
        return view('format.signature',compact('id','contents'));
    }
}
