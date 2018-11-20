<!-- Name of Company Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', isset($user) ? $user->name : null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Address of Company Form Input -->
<div class="form-group @if ($errors->has('address')) has-error @endif">
    {!! Form::label('address', trans('words.Address')) !!}
    {!! Form::text('address', null, ['class' => 'input-body ckeditor']) !!}
    @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
</div>

<!-- Phone of Company Form Input -->
<div class="form-group @if ($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', trans('words.Phone')) !!}
    {!! Form::text('phone', null, ['class' => 'input-body',]) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>

<!-- Email of Company Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('words.Email')) !!}
    {!! Form::text('email', isset($user) ? $user->email : null, ['class' => 'input-body']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- password Form Input -->
<div class="form-group @if ($errors->has('password')) has-error @endif">
    <label for="password">@lang('words.Password')</label>
    {!! Form::password('password', ['class' => 'input-body']) !!}   
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
</div>

<!-- Activity of Company Form Input -->
<div class="form-group @if ($errors->has('activity')) has-error @endif">
    {!! Form::label('activity', trans('words.Activity')) !!}
    {!! Form::text('activity', null, ['class' => 'input-body']) !!}
    @if ($errors->has('activity')) <p class="help-block">{{ $errors->first('activity') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
@endpush