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
        $result = Format::latest()->paginate();
        return view('format.index', compact('result'));
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
        $formato = Preformato::where('id',1)->first();
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $company = Company::where('id',session()->get('Session_Company'))->first();
        $companyselect ='none';
        $mostrar_formato = 'none';
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
        $preformats = Preformato::pluck('name', 'id');

        if($request->get('appointment')){
            $appointment = $request->get('appointment');
            return view('format.new', compact('format', 'formato','clients','companies','companyselect','mostrar_formato','disabled','preformats', 'appointment'));
        }
        return view('format.new', compact('format', 'formato','clients','companies','companyselect','mostrar_formato','disabled','preformats'));
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
            'company_id'         => 'required',
            'client_id'          => 'required',
            'preformat_id'       => 'required',
        ]);
        if($request->company_id == null)
        {
          $request->company_id = session()->get('Session_Company');
        }
        $format = new Format();
        $format->company_id = $request->company_id;
        $format->client_id = $request->client_id;
        $format->preformat_id = $request->preformat_id;
        $format->format = $request->format_expediction;
        $format->status = 1;

        if ($format->save()) {
          if($request->appointment){
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
          }else{
              $alert = ['success', trans_choice('words.Format',1).' '.trans('words.HasAdded')];
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
        $mostrar_formato = 'block';
        $companyselect ='block';
        $state_format = '';
        if (session()->get('Session_Company') != '')
        {
          $companyselect ='none';
        }
        $formato = Format::find($id);
        if ($formato->status == 0)
        {
          $alert = ['success', trans('words.theFormatInactive')];
          return redirect()->route('formats.index')->with('alert',$alert);
        } else {
          if ($formato != '') {
            if ($formato->status == 2){
              $state_format = 'none';
            }
          }
          $companies = Company::with('user')->get()->pluck('user.name', 'id');
          $clients = Client::with('user')->get()->pluck('user.name', 'id');
          $preformats = Preformato::pluck('name', 'id');
          $disabled = 'disabled';
        }

        return view('format.edit', compact('formato','companyselect','mostrar_formato','disabled','companies','clients','preformats','user','state_format'));
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

    public function llenarCabeceraFormato()
    {
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
          } else {
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
        $response = array();
        //Obtenemos el id del formato
        $format_id = $request->input('formato_id');
        $file_id = $request->input('file_id');
        $files = $request->file('input-supports');
        //Directorio destino
        $destinationPath = "uploads/".$format_id."/";
        //Validación datos
        if( !is_null($files) ){
            foreach( $files AS $key => $item )
            {
                if( $item->isValid() ){

					//Verificamos que el peso no supere el limite
					if( $this->getValidSize($item) ){

						//Verificamos que la extensión este permitida
						if( $this->getValidExt($item) ){

							$name_url = $this->getNameFile($destinationPath,$item->getClientOriginalName());
							$upload_success = $item->move($destinationPath, $name_url['name'] );

							if( $upload_success )
							{
								$new_file = array
								(
									'mime_type'     =>  $upload_success->getMimeType(),
									'format_id'     =>  $format_id,
									'nombre_url'    =>  $name_url['url'],
									'user_id'       =>  Auth::id(),
									'extension'     =>  $upload_success->getExtension()
								);

								$insert = File::insertGetId($new_file);
								$new_file['id'] = $insert;
								array_push($response,$new_file);

							}else{
								$response = $this->addError($response,'not_upload',$item);
							}
						}else{
							$response = $this->addError($response,'not_ext_valid',$item);
						}
					}else{
						$response = $this->addError($response,'exceeded_weight',$item);
					}
                }else{
					$response = $this->addError($response,'file_not_valid',$item);
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

    public function downloadPDF($id)
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
        } else {
          $format_pdf = $format->format;
        }
        $supports = File::where('format_id','=',$format->id)->get();
        $file_pdf = '';
        foreach( $supports AS $key => $item )
        {
          //echo "<pre>";print_r($item);echo "</pre>";exit();
          /*if ($item->extension == 'pdf')
          {
            $img = new Spatie\PdfToImage\Pdf(public_path().'/'.$item->nombre_url);
            $img->saveImage(public_path().'/uploads/certificado');
          }*/
            $file_pdf .= '<div class="contenedor_image"><img class="image" src="'.public_path().'/'.$item->nombre_url.'"/></di>';
        }
        $config_format = $estilos->estilos.$format_pdf.$file_pdf.$pagination->estilos;
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($config_format);
        return $pdf->stream();
      } else {
        return redirect()->route('formats.index');
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

	public function addError( $response , $message , $file )
	{
		$text = \Lang::get('words.'.$message);
		$name = $file->getClientOriginalName();

		$text = str_replace("{file}",$name,$text);

		if( isset($response['error']) ){
			$response['error'] .= " <li>".$text."</li>";
		}else{
			$response['error'] = "<li>".$text."</li>";
		}
		return $response;
	}
}
