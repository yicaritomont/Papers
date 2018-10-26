@extends('layouts.app')

@section('title', 'Edit Inspection Type ' . $type->name)

@section('content')

<div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
    <a href="{{ route('inspectiontypes.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.Edit') {{ $type->name  }}</h3>                    
        </div>
        <div class="panel-body black-letter">
            {!! Form::model($type,['method' => 'PUT', 'route' => [ 'inspectiontypes.update', $type->id]]) !!}
            @include('inspection_type._form')

            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary'])!!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection