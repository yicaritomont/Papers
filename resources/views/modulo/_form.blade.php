<!-- name for modules -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('modulo', trans('words.ManageModulo') ) !!}
    {!! Form::text('name', old('name'), ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>