{{-- {{dd($errors)}} --}}

<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('profession_id')) has-error @endif">
    <label for="name">{{trans_choice('words.Profession', 1)}}</label>
    {!! Form::select('profession_id',$professions,null, array('class' => 'input-body', 'required', 'placeholder' => trans('words.ChooseOption'))) !!}
    @if ($errors->has('profession_id')) <p class="help-block">{{ $errors->first('profession_id') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('inspector_type_id')) has-error @endif">
    <label for="name">{{trans_choice('words.InspectorType', 1)}}</label>
    {!! Form::select('inspector_type_id',$inspector_types,null, array('class' => 'input-body','required', 'placeholder' => trans('words.ChooseOption'))) !!}
    @if ($errors->has('inspector_type_id')) <p class="help-block">{{ $errors->first('inspector_type_id') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Identification')</label>
    {!! Form::text('identification', null, ['class' => 'input-body', 'placeholder' => 'Identification']) !!}
    @if ($errors->has('identification')) <p class="help-block">{{ $errors->first('identification') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('phone')) has-error @endif">
    <label for="name">@lang('words.Phone')</label>
    {!! Form::text('phone', null, ['class' => 'input-body', 'placeholder' => 'Phone']) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('addres')) has-error @endif">
    <label for="name">@lang('words.Addres')</label>
    {!! Form::text('addres', null, ['class' => 'input-body', 'placeholder' => 'Addres']) !!}
    @if ($errors->has('addres')) <p class="help-block">{{ $errors->first('addres') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
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