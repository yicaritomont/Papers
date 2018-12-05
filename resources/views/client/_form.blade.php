<!-- Name of Client Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', isset($user) ? $user->name : null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Identificacion of Client Form Input -->
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    {!! Form::label('identification', trans('words.Identification')) !!}
    {!! Form::text('identification', null, ['class' => 'input-body']) !!}
    @if ($errors->has('identification')) <p class="help-block">{{ $errors->first('identification') }}</p> @endif
</div>

<!-- Email of Client Form Input -->
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

<!-- Phone of Client Form Input -->
<div class="form-group @if ($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', trans('words.Phone')) !!}
    {!! Form::text('phone', null, ['class' => 'input-body',]) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>

<!-- Cell Phone of Client Form Input -->
<div class="form-group @if ($errors->has('cell_phone')) has-error @endif">
    {!! Form::label('cell_phone', trans('words.CellPhone')) !!}
    {!! Form::text('cell_phone', null, ['class' => 'input-body']) !!}
    @if ($errors->has('cell_phone')) <p class="help-block">{{ $errors->first('cell_phone') }}</p> @endif
</div>

@if( !auth()->user()->hasRole('Compania') )
    <!-- Companies Form Input -->
    <div class="form-group @if ($errors->has('companies')) has-error @endif">
        {!! Form::label('companies', trans_choice('words.Company', 2)) !!}
        {!! Form::select('companies', $companies, isset($user) ? $user->companies->pluck('id')->toArray() : null,  ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')]) !!}
        @if ($errors->has('companies')) <p class="help-block">{{ $errors->first('companies') }}</p> @endif
    </div>
@endif

@push('scripts')
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
@endpush