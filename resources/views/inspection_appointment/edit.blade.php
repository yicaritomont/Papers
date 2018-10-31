@extends('layouts.app')

@section('title', 'Edit Inspection Appointment ')

@section('content')

<div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
    <a href="{{ route('inspectionappointments.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.Edit') {{ $inspection_appointment->name  }}</h3>    
        </div>
        <div class="panel-body black-letter">
            {!! Form::model($inspection_appointment,['method' => 'PUT', 'route' => [ 'inspectionappointments.update', $inspection_appointment->id]]) !!}
            @include('inspection_appointment._form')

            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary'])!!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection