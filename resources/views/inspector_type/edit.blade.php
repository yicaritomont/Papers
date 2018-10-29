@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.InspectorType',1).' - "'.$type->name.'", ')

@section('content')

<div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
    <a href="{{ route('inspectortypes.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.Edit') {{ $type->name  }}</h3>                    
        </div>
        <div class="panel-body black-letter">
            {!! Form::model($type,['method' => 'PUT', 'route' => [ 'inspectortypes.update', $type->id]]) !!}
            @include('inspector_type._form')

            {!! Form::submit(trans('words.SaveChanges'), ['class' => 'btn btn-primary'])!!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection