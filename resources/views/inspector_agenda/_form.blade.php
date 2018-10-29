<!-- Date of Inspector Agenda Form Date -->
<div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', trans('words.Date')) !!}
    <div class="input-group date">
        {!! Form::text('date', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>

<!-- Start Time of Inspector Agenda Form Time -->
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
</div>

<!-- Inspector of Headquarters Form Select -->
<div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
    {!! Form::label('inspector_id', trans('words.Inspectors')) !!}
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
    {!!Form::select('inspector_id', $inspectors->pluck('name', 'id'), isset($inspectorAgenda) ? $inspectorAgenda->inspector_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('inspector_id')) <p class="help-block">{{ $errors->first('inspector_id') }}</p> @endif
</div>

<!-- Headquarters of Headquarters Form Select -->
<div class="form-group @if ($errors->has('headquarters_id')) has-error @endif">
    {!! Form::label('headquarters_id', str_plural(trans('words.Headquarters'),2)) !!}
    {{-- <select name="headquarters_id" id="headquarters_id" class="input-body">
        <option value="">@lang('words.ChooseOption')</option>
        @foreach($headquarters as $item)
            <option value="{{$item->id}}" 
            @if(isset($inspectorAgenda))
            {{ $inspectorAgenda->headquarters_id === $item->id ? 'selected' : '' }}
            @endif
            >{{$item->name}}</option>
        @endforeach
    </select> --}}
    {!!Form::select('headquarters_id', $headquarters->pluck('name', 'id'), isset($inspectorAgenda) ? $inspectorAgenda->headquarters_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('headquarters_id')) <p class="help-block">{{ $errors->first('headquarters_id') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
