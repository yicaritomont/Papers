<!-- Name of Client Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Lastname of Client Form Input -->
<div class="form-group @if ($errors->has('lastname')) has-error @endif">
    {!! Form::label('lastname', trans('words.Lastname')) !!}
    {!! Form::text('lastname', null, ['class' => 'input-body']) !!}
    @if ($errors->has('lastname')) <p class="help-block">{{ $errors->first('lastname') }}</p> @endif
</div>

<!-- Phone of Client Form Input -->
<div class="form-group @if ($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', trans('words.Phone')) !!}
    {!! Form::text('phone', null, ['class' => 'input-body',]) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>

<!-- Email of Client Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('words.Email')) !!}
    {!! Form::text('email', null, ['class' => 'input-body']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- Cell Phone of Client Form Input -->
<div class="form-group @if ($errors->has('cell_phone')) has-error @endif">
    {!! Form::label('cell_phone', trans('words.CellPhone')) !!}
    {!! Form::text('cell_phone', null, ['class' => 'input-body']) !!}
    @if ($errors->has('cell_phone')) <p class="help-block">{{ $errors->first('cell_phone') }}</p> @endif
</div>

<!-- Slug of Client Form Input -->
<div class="form-group @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('words.Slug')) !!}
    {!! Form::text('slug', null, ['class' => 'input-body']) !!}
    @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
@endpush