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

{{-- <div class="form-group @if ($errors->has('appointment_location_id')) has-error @endif">
    {!! Form::label('appointment_location_id', trans('words.AppointmentLocation')) !!}
    {!! Form::select('appointment_location_id',$appointment_locations, null, ['class' => 'input-body select2 form-control']) !!}
    <div class="errors"></div>
</div> --}}

@if( !auth()->user()->hasRole('Cliente') )
    <div class="form-group @if ($errors->has('client_id')) has-error @endif">
        {!! Form::label('client_id', trans_choice('words.Client', 1)) !!}
        {{-- {!! Form::text('client_id', trans('words.Select').'  '.trans_choice('words.Contract', 1), ['class' => 'input-body form-control','require', 'disabled']) !!} --}}
        {!! Form::select('client_id', isset($clients) ? $clients : [], null, ['class' => 'input-body select2 form-control client-contract', 'placeholder'=>trans('words.ChooseOption')]) !!}
        <div class="errors"></div>
    </div>
@endif

<div class="form-group @if ($errors->has('appointment_location_id')) has-error @endif">
    {!! Form::label('headquarters_id', trans_choice('words.Headquarters', 1)) !!}
    <div class="loading" id="headquarters_id_loading"></div>
    {!! Form::select('headquarters_id', ['' => trans('words.ChooseOption')], null, ['class' => 'input-body select2 form-control']) !!}
    <div class="errors"></div>
</div>

<div class="form-group @if ($errors->has('contract_id')) has-error @endif">
    {!! Form::label('contract_id', trans_choice('words.Contract', 1)) !!}
    <div class="loading" id="contract_id_loading"></div>
    @if(auth()->user()->hasRole('Admin'))
        {!! Form::select('contract_id', ['' => trans('words.ChooseOption')], null, ['class' => 'input-body select2 form-control']) !!}
    @else
        {!! Form::select('contract_id', isset($contracts) ? $contracts : [], null, ['class' => 'input-body select2 form-control','require', 'placeholder'=>trans('words.ChooseOption')]) !!}
    @endif
    <div class="errors"></div>
</div>

{{-- <div class="form-group @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans_choice('words.Client', 1)) !!}
    {!! Form::select('client_id',$clients, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!}
    <div class="errors"></div>
</div> --}}

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

<input type="hidden" name="inspection_subtype_id" id="inspection_subtype_id">

<input type="hidden" name="company_id" id="company_id">