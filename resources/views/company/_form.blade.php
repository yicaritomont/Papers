<!-- Name of Company Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
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
    {!! Form::label('email', trans('words.E-Mail')) !!}
    {!! Form::text('email', null, ['class' => 'input-body']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- Activity of Company Form Input -->
<div class="form-group @if ($errors->has('activity')) has-error @endif">
    {!! Form::label('activity', trans('words.Activity')) !!}
    {!! Form::text('activity', null, ['class' => 'input-body']) !!}
    @if ($errors->has('activity')) <p class="help-block">{{ $errors->first('activity') }}</p> @endif
</div>

<!-- Slug of Company Form Input -->
<div class="form-group @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('words.Slug')) !!}
    {!! Form::text('slug', null, ['class' => 'input-body']) !!}
    @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
@endpush