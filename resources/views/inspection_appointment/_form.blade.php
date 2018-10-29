<div class="form-group @if ($errors->has('inspector')) has-error @endif">
    <label for="name">@lang('words.Inspectors')</label>
    {!! Form::select('inspector_id',$inspectors,null, array('class' => 'input-body', 'required')) !!}
    @if ($errors->has('inspector')) <p class="help-block">{{ $errors->first('inspector') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('inspection_types')) has-error @endif">
    <label for="name">@lang('words.InspectionType')</label>
    {!! Form::select('inspection_type_id',$inspection_types,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('inspection_types')) <p class="help-block">{{ $errors->first('inspection_types') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('appointment_location')) has-error @endif">
    <label for="name">@lang('words.AppointmentLocation')</label>
    {!! Form::select('appointment_location_id',$appointment_locations,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('appointment_location')) <p class="help-block">{{ $errors->first('appointment_location') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('appointment_state')) has-error @endif">
    <label for="name">@lang('words.Status')</label>
    {!! Form::select('appointment_states_id',$appointment_states,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('appointment_state')) <p class="help-block">{{ $errors->first('appointment_state') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('date')) has-error @endif">
    <label for="name">@lang('words.Date')</label>
    {!! Form::text('date', null, ['class' => 'input-body', 'placeholder' => 'date']) !!}
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>