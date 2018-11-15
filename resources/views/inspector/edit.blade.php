@extends('layouts.app')

@section('title', 'Edit Inspector ' . $inspector->name)

@section('content')

<div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
    <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.Edit') {{ $inspector->name  }}</h3>                  
        </div>
        <div class="panel-body black-letter">
            {!! Form::model($inspector,['method' => 'PUT', 'route' => [ 'inspectors.update', $inspector->id]]) !!}
            @include('inspector._form')

            {!! Form::submit(trans('words.SaveChanges'), ['class' => 'btn btn-primary'])!!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection