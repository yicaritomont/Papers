<!-- Client of Headquarters Form Select -->
<div class="form-group @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans('words.Client')) !!}
    {{-- <select name="client_id" id="client_id" class="input-body">
        <option value="">@lang('words.ChooseOption')</option>
        @foreach($clients as $item)
            <option value="{{$item->id}}" 
            @if(isset($headquarters))
                {{ ($headquarters->client_id === $item->id) ? 'selected' : '' }}
            @endif
            >{{$item->name . ' ' . $item->lastname}}</option>
        @endforeach
    </select> --}}
    {!!Form::select('client_id', $clients->pluck('name', 'id'), isset($headquarters) ? $headquarters->client_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('client_id')) <p class="help-block">{{ $errors->first('client_id') }}</p> @endif
</div>

<!-- City of Headquarters Form Select -->
<div class="form-group @if ($errors->has('cities_id')) has-error @endif">
    {!! Form::label('cities_id', trans('words.City')) !!}
    {{-- <select name="cities_id" id="cities_id" class="input-body">
        <option value="">@lang('words.ChooseOption')</option>
        @foreach($cities as $item)
            <option value="{{$item->id}}" 
            @if(isset($headquarters))
            {{ $headquarters->cities_id === $item->id ? 'selected' : '' }}
            @endif
            >{{$item->name}}</option>
        @endforeach
    </select> --}}
    {!!Form::select('cities_id', $cities->pluck('name', 'id'), isset($headquarters) ? $headquarters->cities_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('cities_id')) <p class="help-block">{{ $errors->first('cities_id') }}</p> @endif
</div>

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

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
