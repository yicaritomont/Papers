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

<!-- password Form Input -->
<div class="form-group @if ($errors->has('password')) has-error @endif">
    <label for="password">@lang('words.Password')</label>
    {!! Form::password('password', ['class' => 'input-body']) !!}   
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
</div>

<!-- Image Form Input -->
<div class="form-group @if ($errors->has('picture')) has-error @endif">
    <label for="picture">@lang('words.Picture')</label>
    {!! Form::file('picture', old('picture'), ['class' => 'input-body', 'type'=>'file', 'accept'=>'image/*']) !!}`
    @if ($errors->has('picture')) <p class="help-block">{{ $errors->first('picture') }}</p> @endif
</div>

<!-- Roles Form Input -->
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    {!! Form::label('roles[]', 'Roles') !!}
    {{-- {{dd($user->roles->pluck('id')->toArray())}} --}}
    {!! Form::select('roles[]', $roles, isset($user) ? $user->roles->pluck('id')->toArray() : null,  ['class' => 'input-body', 'multiple']) !!}
    @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
</div>
{{-- {{dd($user->companies->pluck('id')->toArray())}}
{{dd($user->roles->pluck('id')->toArray())}} --}}
<!-- Companies Form Input -->
<div class="form-group @if ($errors->has('companies')) has-error @endif">
    {!! Form::label('companies[]', 'Companies') !!}
    {!! Form::select('companies[]', $companies, isset($user) ? $user->companies->pluck('id')->toArray() : null,  ['class' => 'input-body', 'multiple']) !!}
    @if ($errors->has('companies')) <p class="help-block">{{ $errors->first('companies') }}</p> @endif
</div>

<!-- Permissions -->
@if(isset($user))
    @include('shared._permissions', ['closed' => 'true', 'model' => $user ])
@endif