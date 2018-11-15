<div class="form-group @if ($errors->has('companies')) has-error @endif">
    {!! Form::label('preformatos[]', trans_choice('words.Preformato', 2)) !!}
    {!! Form::select('preformato_id', $preformatos, null,  ['class' => 'input-body', 'require']) !!}
    @if ($errors->has('preformatos')) <p class="help-block">{{ $errors->first('preformatos') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body','placeholder' => trans('words.Name')]) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name')}}</p> @endif
</div>

<div class="form-group @if ($errors->has('name')) has-error @endif">
</div>
<!--<div class="col-md-12">
    <div class="panel panel-default">
        <label for="name">{{trans_choice('words.Preformato',1)}}</label>
        {!! Form::textarea('preformato',null,['class' => 'ckeditor','id' => 'editor1']) !!}
        @if ($errors->has('preformatos')) <p class="help-block">{{ $errors->first('preformatos') }}</p> @endif
    </div>-->
    <div class="col-md-12">
        <div class="panel panel-default">
          {!!$preformatos->preformato !!}
    </div>
</div>
