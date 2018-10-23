<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Profession')</label>
    {!! Form::select('profession',$professions,null, array('class' => 'chosen-select form-control', 'required')) !!}
    @if ($errors->has('profession')) <p class="help-block">{{ $errors->first('profession') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.InspectorType')</label>
    {!! Form::select('inspector_type',$inspector_types,null, array('class' => 'chosen-select form-control','require')) !!}
    @if ($errors->has('inspector_type')) <p class="help-block">{{ $errors->first('inspector_type') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Identification')</label>
    {!! Form::text('identification', null, ['class' => 'input-body', 'placeholder' => 'Identification']) !!}
    @if ($errors->has('identification')) <p class="help-block">{{ $errors->first('identification') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Phone')</label>
    {!! Form::text('	phone', null, ['class' => 'input-body', 'placeholder' => 'Phone']) !!}
    @if ($errors->has('	phone')) <p class="help-block">{{ $errors->first('	phone') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Addres')</label>
    {!! Form::text('	addres', null, ['class' => 'input-body', 'placeholder' => 'Addres']) !!}
    @if ($errors->has('	addres')) <p class="help-block">{{ $errors->first('	addres') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Email')</label>
    {!! Form::text('	email', null, ['class' => 'input-body', 'placeholder' => 'Email']) !!}
    @if ($errors->has('	email')) <p class="help-block">{{ $errors->first('	email') }}</p> @endif
</div>