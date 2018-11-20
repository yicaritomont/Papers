<!--<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body','placeholder' => trans('words.Name')]) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name')}}</p> @endif
</div>-->
    <div class="col-md-12">
        <div class="panel panel-default" name="format">
          {!! $formato->format!!}
    </div>
</div>
