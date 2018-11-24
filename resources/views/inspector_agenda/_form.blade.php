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

<!-- Inspector of Headquarters Form Select -->
<div class="form-group">
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
    <div class="errors"></div>
</div>

<!-- Country of Headquarters Form Select -->
<div class="form-group">
    {!! Form::label('country', trans('words.Country')) !!}
    {!!Form::select('country', $countries, isset($inspectorAgenda) ? $inspectorAgenda->country : null, ['class' => ['input-body', 'country'], 'data-route'=>route('inspectoragendas.cities'), 'placeholder' => trans('words.ChooseOption')])!!}
    <div class="errors"></div>
</div>

<div class="form-group">
    {!! Form::label('city_id', trans('words.City')) !!}
    {{-- {!! Form::select('city_id', null, null, ['class' => 'input-body','require', 'placeholder'=>trans('words.ChooseOption')]) !!} --}}
    <select id="city_id" name="city_id" class="input-body city_id">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    <div class="errors"></div>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
