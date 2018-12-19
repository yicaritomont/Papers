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

@if( !auth()->user()->hasRole('Cliente') )
    <!-- Client of Headquarters Form Select -->
    <div class="form-group @if ($errors->has('client_id')) has-error @endif">
        {!! Form::label('client_id', trans_choice('words.Client', 1)) !!}
        {!!Form::select('client_id', $clients, isset($headquarters) ? $headquarters->client_id : null, ['class' => 'input-body form-control select2', 'placeholder' => trans('words.ChooseOption')])!!}
        @if ($errors->has('client_id')) <p class="help-block">{{ $errors->first('client_id') }}</p> @endif
    </div>
@endif

<!-- Location of Headquarters Form Select -->
<div class="form-group @if ($errors->has('latitude') || $errors->has('longitude')) has-error @endif">
    <label>Ubicación</label>
    
    <div id="map"></div>

    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    @if ($errors->has('latitude') || $errors->has('longitude')) <p class="help-block">@lang('words.ErrorMapForm')</p> @endif
</div>

@section('scripts')

    @include ('shared._formMap')
    
@endsection