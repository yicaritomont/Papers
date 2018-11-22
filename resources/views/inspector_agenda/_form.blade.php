{{-- <!-- Start Date of Inspector Agenda Form Date -->
<div class="form-group @if ($errors->has('start_date')) has-error @endif">
    {!! Form::label('start_date', trans('words.StartDate')) !!}
    <div class="input-group date">
        {!! Form::text('start_date', null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
</div>

<!-- End Date of Inspector Agenda Form Date -->
<div class="form-group @if ($errors->has('end_date')) has-error @endif">
    {!! Form::label('end_date', trans('words.EndDate')) !!}
    <div class="input-group date">
        {!! Form::text('end_date', null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
</div> --}}

<!-- End Date of Inspector Agenda Form Date -->
<div class="form-group @if ($errors->has('start_date')) has-error @endif">
    {!! Form::label('start_date', trans('words.StartDate').' - ') !!}
    {!! Form::label('end_date', trans('words.EndDate')) !!}
    
    <div class="input-daterange input-group date" id="datepicker">
        <input type="text" class="form-control input-date" name="start_date" id="start_date" autocomplete="off">
        <span class="input-group-addon">@lang('words.To')</span>
        <input type="text" class="form-control input-date" name="end_date" id="end_date" autocomplete="off">
    </div>
</div>

{{-- <!-- Start Time of Inspector Agenda Form Time -->
<div class="form-group @if ($errors->has('start_time')) has-error @endif">
    {!! Form::label('start_time', trans('words.StartTime')) !!}
    <div class="input-group clockpicker" data-autoclose="true">
        {!! Form::text('start_time', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
    </div>
    
    @if ($errors->has('start_time')) <p class="help-block">{{ $errors->first('start_time') }}</p> @endif
</div>

<!-- End Time of Inspector Agenda Form Time -->
<div class=" @if ($errors->has('end_time')) has-error @endif">
    {!! Form::label('end_time', trans('words.EndTime')) !!}
    <div class="input-group clockpicker" data-autoclose="true">
        {!! Form::text('end_time', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
    </div>
    @if ($errors->has('end_time')) <p class="help-block">{{ $errors->first('end_time') }}</p> @endif
</div> --}}

<!-- Inspector of Headquarters Form Select -->
<div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
    {!! Form::label('inspector_id', trans_choice("words.Inspector", 1)) !!}
    {{-- <select name="inspector_id" id="inspector_id" class="input-body">
        <option value="">@lang('words.ChooseOption')</option>
        @foreach($inspectors as $item)
            <option value="{{$item->id}}" 
            @if(isset($inspectorAgenda))
            {{ $inspectorAgenda->inspector_id === $item->id ? 'selected' : '' }}
            @endif
            >{{$item->name}}</option>
        @endforeach
    </select> --}}
    {!!Form::select('inspector_id', $inspectors, isset($inspectorAgenda) ? $inspectorAgenda->inspector_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('inspector_id')) <p class="help-block">{{ $errors->first('inspector_id') }}</p> @endif
</div>

{{-- <!-- Headquarters of Headquarters Form Select -->
<div class="form-group @if ($errors->has('headquarters_id')) has-error @endif">
    {!! Form::label('headquarters_id', str_plural(trans('words.Headquarters'),2)) !!}
    {!!Form::select('headquarters_id', $headquarters->pluck('name', 'id'), isset($inspectorAgenda) ? $inspectorAgenda->headquarters_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('headquarters_id')) <p class="help-block">{{ $errors->first('headquarters_id') }}</p> @endif
</div> --}}

<!-- Country of Headquarters Form Select -->
<div class="form-group @if ($errors->has('country')) has-error @endif">
    {!! Form::label('country', trans('words.Country')) !!}
    {!!Form::select('country', $countries, isset($inspectorAgenda) ? $inspectorAgenda->country : null, ['class' => ['input-body', 'country'], 'data-route'=>route('inspectoragendas.cities'), 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('country')) <p class="help-block">{{ $errors->first('country') }}</p> @endif
</div>

{{-- <!-- City of Headquarters Form Select -->
<div class="form-group @if ($errors->has('headquarters_id')) has-error @endif">
    {!! Form::label('headquarters_id', trans('words.City')) !!}
    {!!Form::select('headquarters_id', $headquarters->pluck('name', 'id'), isset($inspectorAgenda) ? $inspectorAgenda->headquarters_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('headquarters_id')) <p class="help-block">{{ $errors->first('headquarters_id') }}</p> @endif
</div> --}}

<div class="form-group @if ($errors->has('city_id')) has-error @endif">
    {!! Form::label('city_id', trans('words.City')) !!}
    {{-- {!! Form::select('city_id', null, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!} --}}
    <select id="city_id" name="city_id" class="input-body city_id">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    @if ($errors->has('inspection_types')) <p class="help-block">{{ $errors->first('inspection_types') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
