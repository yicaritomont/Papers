<div class="form-group @if ($errors->has('companies')) has-error @endif">
    {!! Form::label('inspection_subtypes[]', trans_choice('words.InspectionSubtype', 1)) !!}
    {!! Form::select('inspection_subtype_id', $inspection_subtypes, null,  ['class' => 'input-body form-control select2', 'require']) !!}
    @if ($errors->has('inspection_subtypes')) <p class="help-block">{{ $errors->first('inspection_subtypes') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('company_id')) has-error @endif">
    {!! Form::label('company_id', trans_choice('words.Company', 1)) !!}
    {!! Form::select('company_id', $companies, null,  ['class' => 'input-body form-control select2', 'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('company_id')) <p class="help-block">{{ $errors->first('company_id') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body','placeholder' => trans('words.Name')]) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name')}}</p> @endif
</div>

<div class="form-group @if ($errors->has('format')) has-error @endif">
</div>
{{-- <div class="col-md-12"> --}}
    <div class="panel panel-default">
        <label for="name">{{trans_choice('words.header',1)}}</label>
        {!! Form::textarea('header',null,['class' => 'ckeditor','id' => 'header']) !!}
        @if ($errors->has('header')) <p class="help-block">{{ $errors->first('header') }}</p> @endif
    </div>
    <div class="panel panel-default">
        <label for="name">{{trans_choice('words.Preformato',1)}}</label>
        {!! Form::textarea('format',null,['class' => 'ckeditor','id' => 'editor1']) !!}
        @if ($errors->has('format')) <p class="help-block">{{ $errors->first('format') }}</p> @endif
    </div>
{{-- </div> --}}