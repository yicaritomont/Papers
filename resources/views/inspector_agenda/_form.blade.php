<!-- Range Date of Inspector Agenda Form Date -->
<div class="form-group">
    {!! Form::label('start_date', trans('words.StartDate').' - ') !!}
    {!! Form::label('end_date', trans('words.EndDate')) !!}
    
    <div class="input-group date-range-inputs">
        <input type="text" class="form-control input-date" name="start_date" id="start_date" autocomplete="off">
        <span class="input-group-addon">@lang('words.To')</span>
        <input type="text" class="form-control input-date" name="end_date" id="end_date" autocomplete="off">
    </div>
    <div class="errors"></div>
</div>

@if( !auth()->user()->hasRole('Inspector') )
    <!-- Inspector of Headquarters Form Select -->
    <div class="form-group">
        {!! Form::label(isset($edit) ? $edit.'inspector_id' :  'inspector_id', trans_choice("words.Inspector", 1)) !!}
        {!!Form::select('inspector_id', $inspectors, isset($inspectorAgenda) ? $inspectorAgenda->inspector_id : null, ['class' => 'input-body select2 form-control', 'placeholder' => trans('words.ChooseOption'), 'id' => isset($edit) ? $edit.'inspector_id' :  'inspector_id'])!!}
        <div class="errors"></div>
    </div>
@endif

<!-- Country of Agenda Form Select -->
<div class="form-group">
    {!! Form::label(isset($edit) ? $edit.'country' :  'country', trans('words.Country')) !!}
    {!!Form::select('country', $countries, isset($inspectorAgenda) ? $inspectorAgenda->country : null, ['class' => 'input-body country select2 form-control', 'placeholder' => trans('words.ChooseOption'), 'id' => isset($edit) ? $edit.'country' :  'country'])!!}
    <div class="errors"></div>
</div>

<div class="form-group">
    {!! Form::label(isset($edit) ? $edit.'city_id' :  'city_id', trans('words.City')) !!}
    <div class="loading city_id_loading"></div>
    {{-- {!! Form::select('city_id', null, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!} --}}
    <select id="{{ isset($edit) ? $edit :  ''}}city_id" name="city_id" class="input-body city_id select2 form-control">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    <div class="errors"></div>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
