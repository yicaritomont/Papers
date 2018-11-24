<div class="form-group @if ($errors->has('company')) has-error @endif" id="motrarcompanies" style="display:{!! $companyselect !!};">
    <label for="name">@lang('words.Company')</label>
    {!! Form::select('company_id',$companies, isset($user) ? $user->companies->pluck('id')->toArray() : null, ['class' => 'input-body','id' => 'company_formato',$disabled,'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('company')) <p class="help-block">{{ $errors->first('company')}}</p> @endif
</div>
<div class="form-group @if ($errors->has('client')) has-error @endif">
    <label for="name">@lang('words.Client')</label>
    <div  id="contenedor_client">
      {!! Form::select('client_id',$clients, null, ['class' => 'input-body','id' => 'cliente_formato',$disabled,'placeholder' => trans('words.ChooseOption')]) !!}
      @if ($errors->has('client')) <p class="help-block">{{ $errors->first('client')}}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('preformat')) has-error @endif" id="contenedor_preformat">
    <label for="name">@lang('words.Preformato')</label>
    {!! Form::select('preformat_id',$preformats, null, ['class' => 'input-body','id' => 'format_preformato',$disabled,'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('preformat')) <p class="help-block">{{ $errors->first('preformat')}}</p> @endif
</div>
    <div id="plantilla_formato" style="display:{!! $mostrar_formato !!};">{!! $formato->format!!}</div>
    <div class="col-md-12">
        <div class="panel panel-default" name="format"  id="contenedor_formato" style="display:none;">

    </div>
</div>
