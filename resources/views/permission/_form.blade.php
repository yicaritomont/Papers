<!-- name for a New permission -->
<div class="form-group @if ($errors->has('permission')) has-error @endif">
    <label for="name">@lang('words.Permissions')</label>
    {!! Form::text('permission', null, ['class' => 'input-body']) !!}
    @if ($errors->has('permission')) <p class="help-block">{{ $errors->first('permission') }}</p> @endif
</div>