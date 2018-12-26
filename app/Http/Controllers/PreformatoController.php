<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preformato;
use App\InspectionSubtype;
use App\Company;

class PreformatoController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('Admin')){
            $result = Preformato::all();

            return view('preformato.index', compact('result'));
        }

        $companySlug = Company::find(session()->get('Session_Company'))->slug;
        
        return view('preformato.index', compact('companySlug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $preformato = Preformato::pluck('name', 'id');
        $inspection_subtypes = InspectionSubtype::with('inspection_types')->get()->pluck('subtype_type', 'id');

        return view('preformato.new', compact('preformato','inspection_subtypes', 'companies'));
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

        $this->validate($request, [
            'name' => 'required|unique:preformatos|min:2',
            'inspection_subtype_id' => 'required',
            'header' => 'required',
            'format' => 'required',
            'company_id' => 'required',
        ]);

        $preformato = new Preformato();
        $preformato->name = $request->name;
        $preformato->inspection_subtype_id = $request->inspection_subtype_id;
        $preformato->header = $request->header;
        $preformato->format = $request->format;
        $preformato->status = 1;
        $preformato->company_id = $request->company_id;

        if ($preformato->save()) {
            $alert = ['success', trans_choice('words.Preformato',1).' '.trans('words.HasAdded')];
        } else {
            $alert = ['success', trans('words.UnableCreate').' '.trans_choice('words.Preformato',1)];
        }
        return redirect()->route('preformatos.index')->with('alert', $alert);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function show(Support $support)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Preformato  $preformato
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companies = Company::with('user')->get()->pluck('user.name', 'id');
        $inspection_subtypes = InspectionSubtype::with('inspection_types')->get()->pluck('subtype_type', 'id');
        $preformato = Preformato::find($id);

        if(CompanyController::compareCompanySession([$preformato->company]))
        {
            if ($preformato->status == 0)
            {
                $alert = ['success', trans('words.thePreformatInactive')];
                return redirect()->route('preformatos.index')->with('alert',$alert);
            }
            return view('preformato.edit', compact('preformato','inspection_subtypes', 'companies'));

        }
        else
        {
            $alert = ['error', 'This action is unauthorized.'];
            return redirect()->route('preformatos.index')->with('alert',$alert);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $preformato = Preformato::findOrFail($id);

        if( !CompanyController::compareCompanySession([$preformato->company]) )
        {
            $alert = ['error', 'This action is unauthorized.'];
            return redirect()->route('preformatos.index')->with('alert',$alert);
        }

        if( !auth()->user()->hasRole('Admin') ){
            $request['company_id'] = session()->get('Session_Company');
        }

        if ($preformato->name != $request->name)
            { $this->validate($request, [
                'name'                  => 'required|unique:preformatos|min:2',
                'inspection_subtype_id' => 'required',
                'format'                => 'required',
                'header'                => 'required',
                'company_id'            => 'required',
            ]);
        }else{
            $this->validate($request, [
                'name'                  => 'required|min:2',
                'inspection_subtype_id' => 'required',
                'format'                => 'required',
                'header'                => 'required',
                'company_id'            => 'required',
            ]);
        }

        $preformato->update($request->except(array('_method','_token')));

        $alert = ['success', trans_choice('words.Preformato',1).' '.trans('words.HasUpdated')];
        return redirect()->route('preformatos.index')->with('alert', $alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $preformat = Preformato::find($id);

      if( !CompanyController::compareCompanySession([$preformat->company]) )
      {
        abort(403, 'This action is unauthorized.');
      }

      //Valida que exista el servicio
      if($preformat)
      {
      switch ($preformat->status)
      {
        case 1 : $preformat->status = 0;
               $accion = 'Desactivó';
          break;

        case 0 : $preformat->status = 1;
               $accion = 'Activó';
          break;

        default : $preformat->status = 0;

            break;
      }

      $preformat->save();
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

}
