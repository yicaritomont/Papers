<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Format;
use App\Preformato;
use App\Company;
use App\Client;
use App\Contract;
use App\User;
use App\Estilo;
use PDF;

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
    public function create()
    {
        $format = Format::pluck('id');
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
          $alert = ['success', trans_choice('words.Format',1).' '.trans('words.HasAdded')];
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
        if ($formato->status == 2){
          $state_format = 'none';
        }
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $clients = Client::with('user')->get()->pluck('user.name', 'id');
        $preformats = Preformato::pluck('name', 'id');
        $disabled = 'disabled';

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
                    json_encode($response = [ 'clients' => $clients]);
        }
        return $response;
    }

    public function downloadPDF($id)
    {
      $format = Format::find($id);
      $estilos = Estilo::find(1);
      $config_format = $estilos->estilos.$format->format;
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($config_format);
      echo "<pre>";print_r($config_format);echo "</pre>";exit();
      return $pdf->stream();
    }
}
