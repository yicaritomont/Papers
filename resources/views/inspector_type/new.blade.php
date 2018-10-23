@extends('layouts.app')

@section('title', 'Create')

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('inspectortypes.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Create')</h3>                    
                </div>
                <div class="panel-body black-letter">
                    {!! Form::open(['route' => ['inspectortypes.store'] ]) !!}
                        @include('inspector_type._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit('Create', ['class' => 'btn-body']) !!}
                    {!! Form::close() !!}
                </div>
            </div>                 
        
    </div>
@endsection