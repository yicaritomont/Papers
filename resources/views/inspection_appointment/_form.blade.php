<!-- Range Date of Appointment -->
<div class="form-group">
    {!! Form::label('estimated_start_date', trans('words.StartDate').' - ') !!}
    {!! Form::label('estimated_end_date', trans('words.EndDate')) !!}
    
    <div class="input-group date-range-inputs">
        <input type="text" class="form-control input-date" name="estimated_start_date" id="estimated_start_date" autocomplete="off">
        <span class="input-group-addon">@lang('words.To')</span>
        <input type="text" class="form-control input-date" name="estimated_end_date" id="estimated_end_date" autocomplete="off">
    </div>
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
    {!! Form::label('inspector_id', trans_choice("words.Inspector", 1)) !!}
    {!! Form::select('inspector_id',$inspectors, isset($agenda) ? $agenda['inspector_id'] : null, ['class' => 'input-body', 'placeholder'=>trans('words.ChooseOption')]) !!}
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('inspection_type_id')) has-error @endif">
    {!! Form::label('inspection_type_id', trans_choice('words.InspectionType', 1)) !!}
    {!! Form::select('inspection_type_id',$inspection_types,null, ['class' => ['input-body', 'inspection_type_id'] ,'require', 'data-route'=>route('inspectionappointments.subtypes'), 'placeholder'=>trans('words.ChooseOption')]) !!}
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('inspection_subtype_id')) has-error @endif">
    {!! Form::label('inspection_subtype_id', trans_choice('words.InspectionSubtype', 1)) !!}
    <select id="inspection_subtype_id" name="inspection_subtype_id" class="input-body inspection_subtype_id">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('appointment_location_id')) has-error @endif">
    {!! Form::label('appointment_location_id', trans('words.AppointmentLocation')) !!}
    {!! Form::select('appointment_location_id',$appointment_locations, null, array('class' => 'input-body','require')) !!}
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('contract_id')) has-error @endif">
    {!! Form::label('contract_id', trans_choice('words.Contract', 1)) !!}
    {!! Form::select('contract_id',$contracts, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!}
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans_choice('words.Client', 2)) !!}
    {!! Form::select('client_id',$clients, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!}
    <div class="errors"></div>
</div>

{{-- <div class="form-group @if ($errors->has('estimated_start_date')) has-error @endif">
    {!! Form::label('estimated_start_date', trans('words.StartDate')) !!}
    <div class="input-group date">
        {!! Form::text('estimated_start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('estimated_end_date')) has-error @endif">
    {!! Form::label('estimated_end_date', trans('words.EndDate')) !!}
    <div class="input-group date">
        {!! Form::text('estimated_end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    <div class="errors"></div>
</div> --}}

@if(isset($agenda))
    <input type="hidden" name="inspector_id" value="{{$agenda['inspector_id']}}">
@endif