
<div class="form-group @if ($errors->has('inspector')) has-error @endif">
    {!! Form::label('inspector_id', trans('words.Inspectors')) !!}
    {!! Form::select('inspector_id',$inspectors, isset($agenda) ? $agenda['inspector_id'] : null, array('class' => 'input-body')) !!}
    @if ($errors->has('inspector')) <p class="help-block">{{ $errors->first('inspector') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('inspection_type_id')) has-error @endif">
    {!! Form::label('inspection_type_id', trans('words.InspectionType')) !!}
    {!! Form::select('inspection_type_id',$inspection_types,null, ['class' => ['input-body', 'inspection_type_id'] ,'require', 'data-route'=>route('inspectionappointments.subtypes'), 'placeholder'=>trans('words.ChooseOption')]) !!}
    @if ($errors->has('inspection_types')) <p class="help-block">{{ $errors->first('inspection_types') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('inspection_subtype_id')) has-error @endif">
    {!! Form::label('inspection_subtype_id', trans('words.InspectionType')) !!}
    <select id="inspection_subtype_id" name="inspection_subtype_id" class="input-body inspection_subtype_id">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    @if ($errors->has('inspection_types')) <p class="help-block">{{ $errors->first('inspection_types') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('appointment_location')) has-error @endif">
    {!! Form::label('appointment_location_id', trans('words.AppointmentLocation')) !!}
    {!! Form::select('appointment_location_id',$appointment_locations,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('appointment_location')) <p class="help-block">{{ $errors->first('appointment_location') }}</p> @endif
</div>

{{-- <div class="form-group @if ($errors->has('appointment_state')) has-error @endif">
    {!! Form::label('appointment_states_id', trans('words.Status')) !!}
    {!! Form::select('appointment_states_id',$appointment_states,null, array('class' => 'input-body','require')) !!}
    @if ($errors->has('appointment_state')) <p class="help-block">{{ $errors->first('appointment_state') }}</p> @endif
</div> --}}
{{-- <div class="form-group @if ($errors->has('date')) has-error @endif">
    <label for="name">@lang('words.Date')</label>
    {!! Form::text('date', null, ['class' => 'input-body', 'placeholder' => 'date']) !!}
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div> --}}
<div class="form-group @if ($errors->has('start_date')) has-error @endif">
    {!! Form::label('start_date', trans('words.startDate')) !!}
    <div class="input-group start_date">
        {!! Form::text('start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('end_date')) has-error @endif">
    {!! Form::label('end_date', trans('words.EndDate')) !!}
    <div class="input-group end_date">
        {!! Form::text('end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>

@if(isset($agenda))
    <input type="hidden" name="inspector_id" value="{{$agenda['inspector_id']}}">
@endif