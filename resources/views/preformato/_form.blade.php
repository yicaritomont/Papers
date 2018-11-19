<div class="form-group @if ($errors->has('companies')) has-error @endif">
    {!! Form::label('inspection_subtypes[]', trans_choice('words.Inspectionsubtype', 2)) !!}
    {!! Form::select('inspection_subtype_id', $inspection_subtypes, null,  ['class' => 'input-body', 'require']) !!}
    @if ($errors->has('inspection_subtypes')) <p class="help-block">{{ $errors->first('inspection_subtypes') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body','placeholder' => trans('words.Name')]) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name')}}</p> @endif
</div>

<div class="form-group @if ($errors->has('format')) has-error @endif">
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <label for="name">{{trans_choice('words.Preformato',1)}}</label>
        {!! Form::textarea('format',null,['class' => 'ckeditor','id' => 'editor1']) !!}
        @if ($errors->has('format')) <p class="help-block">{{ $errors->first('format') }}</p> @endif
    </div>
</div>
