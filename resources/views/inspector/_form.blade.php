<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Profession')</label>
    {!! Form::select('profession_id',$professions,null, array('class' => 'input-body', 'required')) !!}
    @if ($errors->has('profession')) <p class="help-block">{{ $errors->first('profession') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.InspectorType')</label>
    {!! Form::select('inspector_type_id',$inspector_types,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('inspector_type')) <p class="help-block">{{ $errors->first('inspector_type') }}</p> @endif
</div>
<!--<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Country')</label>
    {!! Form::select('country_id',$countries,null, array('class' => 'input-body', 'required')) !!}
    @if ($errors->has('country')) <p class="help-block">{{ $errors->first('country') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.City')</label>
    {!! Form::select('city_id',$cities,null, array('class' => 'input-body','required')) !!}
    @if ($errors->has('city')) <p class="help-block">{{ $errors->first('city') }}</p> @endif
</div>-->
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Identification')</label>
    {!! Form::text('identification', null, ['class' => 'input-body', 'placeholder' => 'Identification']) !!}
    @if ($errors->has('identification')) <p class="help-block">{{ $errors->first('identification') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Phone')</label>
    {!! Form::text('phone', null, ['class' => 'input-body', 'placeholder' => 'Phone']) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Addres')</label>
    {!! Form::text('addres', null, ['class' => 'input-body', 'placeholder' => 'Addres']) !!}
    @if ($errors->has('addres')) <p class="help-block">{{ $errors->first('addres') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Email')</label>
    {!! Form::text('email', null, ['class' => 'input-body', 'placeholder' => 'Email']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- Companies Form Input -->
<div class="form-group @if ($errors->has('companies')) has-error @endif">
    {!! Form::label('companies[]', trans_choice('words.Company', 2)) !!}
    {!! Form::select('companies[]', $companies, isset($user) ? $user->companies->pluck('id')->toArray() : null,  ['class' => 'input-body', 'multiple']) !!}
    @if ($errors->has('companies')) <p class="help-block">{{ $errors->first('companies') }}</p> @endif
</div>