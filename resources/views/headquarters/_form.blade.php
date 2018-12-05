<!-- Name of Headquarters Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Address of Headquarters Form Input -->
<div class="form-group @if ($errors->has('address')) has-error @endif">
    {!! Form::label('address', trans('words.Address')) !!}
    {!! Form::text('address', null, ['class' => 'input-body ckeditor']) !!}
    @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
</div>

<!-- Client of Headquarters Form Select -->
<div class="form-group @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans_choice('words.Client', 1)) !!}
    {!!Form::select('client_id', $clients, isset($headquarters) ? $headquarters->client_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('client_id')) <p class="help-block">{{ $errors->first('client_id') }}</p> @endif
</div>

<!-- Country of Headquarters Form Select -->
<div class="form-group  @if ($errors->has('country')) has-error @endif">
    {!! Form::label('country', trans('words.Country')) !!}
    {!!Form::select('country', $countries, isset($headquarters) ? $headquarters->cities->countries_id : null, ['class' => ['input-body', 'country', 'form-control', 'chosen-select'], 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('country')) <p class="help-block">{{ $errors->first('country') }}</p> @endif
</div>

<!-- City of Headquarters Form Select -->
<div class="form-group @if ($errors->has('cities_id')) has-error @endif">
    {!! Form::label('cities_id', trans('words.City')) !!}
    {{-- {!!Form::select('cities_id', null, isset($headquarters) ? $headquarters->cities_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!} --}}
    <select id="cities_id" name="cities_id" class="input-body city_id form-control chosen-select">
        <option selected value>@lang('words.ChooseOption')</option>
    </select>
    @if ($errors->has('cities_id')) <p class="help-block">{{ $errors->first('cities_id') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
