<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body']) !!}   
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- email Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="email">@lang('words.E-Mail')</label>
    {!! Form::text('email', old('email'), ['class' => 'input-body']) !!}    
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- Image Form Input -->
<div class="form-group @if ($errors->has('picture')) has-error @endif">
    <label for="picture">@lang('words.Picture')</label>
    {!! Form::file('picture', old('picture'), ['class' => 'input-body', 'type'=>'file', 'accept'=>'image/*']) !!}`
    @if ($errors->has('picture')) <p class="help-block">{{ $errors->first('picture') }}</p> @endif
</div>