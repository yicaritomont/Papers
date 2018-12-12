<div class="form-group @if ($errors->has('company_id')) has-error @endif" id="motrarcompanies" style="display:{!! $companyselect !!};">
    <label for="name">@choice('words.Company',1)</label>
    {!! Form::select('company_id',$companies, isset($user) ? $user->companies->pluck('id')->toArray() : null, ['class' => 'input-body select2 form-control','id' => 'company_formato',$disabled,'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('company_id')) <p class="help-block">{{ $errors->first('company_id')}}</p> @endif
</div>
<div class="form-group @if ($errors->has('client_id')) has-error @endif">
    <label for="name">@choice('words.Client', 1)</label>
    <div class="loading" id="cliente_formato_loading"></div>
    <div  id="contenedor_client">
      {!! Form::select('client_id',['' => trans('words.ChooseOption')], null, ['class' => 'input-body select2 form-control', 'id' => 'cliente_formato',$disabled]) !!}
      @if ($errors->has('client_id')) <p class="help-block">{{ $errors->first('client_id')}}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('preformat_id')) has-error @endif" id="contenedor_preformat">
    <label for="name">@choice('words.Preformato',1)</label>
    {!! Form::select('preformat_id',$preformats, null, ['class' => 'input-body select2 form-control','id' => 'format_preformato',$disabled,'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('preformat_id')) <p class="help-block">{{ $errors->first('preformat_id')}}</p> @endif
</div>
<div id="plantilla_formato" class="col-xs-12" style="display:{!! $mostrar_formato !!};overflow-y: scroll;">{!! $formato->format!!}</div>
    <div class="panel panel-default col-xs-12" name="format"   id="contenedor_formato" style="display:none;overflow-y: scroll;">
</div>
@if(isset($appointment))
    <input type="hidden" name="appointment" value="{{ $appointment }}">
@endif
