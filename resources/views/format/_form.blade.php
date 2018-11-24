<div class="form-group @if ($errors->has('company')) has-error @endif" id="motrarcompanies" style="display:{!! $companyselect !!};">
    <label for="name">@lang('words.Company')</label>
    {!! Form::select('company_id',$companies, null, ['class' => 'input-body','id' => 'company_formato','placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('company')) <p class="help-block">{{ $errors->first('company')}}</p> @endif
</div>
<div class="form-group @if ($errors->has('client')) has-error @endif" id="contenedor_client">
    <label for="name">@lang('words.Client')</label>
    {!! Form::select('client_id',$clients, null, ['class' => 'input-body','id' => 'cliente_formato','placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('client')) <p class="help-block">{{ $errors->first('client')}}</p> @endif
</div>
    <div id="plantilla_formato" style="display:none;">{!! $formato->format!!}</div>
    <div class="col-md-12">
        <div class="panel panel-default" name="format"  id="contenedor_formato" style="display:none;">

    </div>
</div>
