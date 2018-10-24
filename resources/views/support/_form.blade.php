<!-- Inspection_format of Client Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Date of Client Form Input -->
<div class="form-group @if ($errors->has('lastname')) has-error @endif">
    {!! Form::label('lastname', trans('words.Lastname')) !!}
    {!! Form::text('lastname', null, ['class' => 'input-body']) !!}
    @if ($errors->has('lastname')) <p class="help-block">{{ $errors->first('lastname') }}</p> @endif
</div>

<!-- Route of Client Form Input -->
<div class="form-group @if ($errors->has('route')) has-error @endif">
    {!! Form::label('route', trans('words.Route')) !!}
    {!! Form::text('route', null, ['class' => 'input-body',]) !!}
    @if ($errors->has('route')) <p class="help-block">{{ $errors->first('route') }}</p> @endif
</div>

<!-- Name of Client Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
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