<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Format;
use App\Preformato;
use App\Company;
use App\Client;
use App\Contract;

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
        $format = Format::pluck('name', 'id');
        $formato = Preformato::where('id',1)->first();
        $companies = Company::pluck('name','id');
        $company = Company::where('id',session()->get('Session_Company'))->first();
        $companyselect ='none';
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

        return view('format.new', compact('format', 'formato','clients','companies','companyselect'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $format = new Format();
        $format->name = 'empresa prueba';
        $format->format = $request->format_expediction;
        $format->state = 1;

      if ($format->save()) {
          flash(trans_choice('words.format',1).' '.trans('words.HasAdded'));
      } else {
          flash()->error(trans('words.UnableCreate').' '.trans_choice('words.format',1));
      }
      return redirect()->route('formats.index');
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
        return view('format.edit', compact('formato'));
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
        if ($formato->name != $request->name){
           $this->validate($request, [
                //'name' => 'required|unique:formats|min:2',
                'format_expediction' => 'required',
            ]);
        }

        $formato->name = 'empresa prueba modificada';
        $formato->format = $request->format_expediction;
        $formato->state = 1;
        $formato->save();

        flash()->success(trans_choice('words.Format',1).' '.trans('words.HasUpdated'));
        return redirect()->route('formats.index');
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
        } else {
          $company = Company::where('id',session()->get('Session_Company'))->first();
        }
        $company->image ='<img width="40%" src="../../public/images/empresa.jpg">';
        $company->iso ='<img width="40%" src="../../public/images/iso.jpg">';
        $contract = Contract::where('company_id',$company->id)
          ->where('client_id','=',$client->id)
          ->first();

        json_encode($response = [
            'company' => $company,
            'client' => $client,
            'contract' => $contract,
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
}
