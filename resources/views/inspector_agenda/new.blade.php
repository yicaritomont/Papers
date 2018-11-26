@extends('layouts.app')

@section('title', trans('words.Create'))

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
                @if(isset($view))
                    <a href="{{ route('inspectoragendas.view') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
                @else
                    <a href="{{ route('inspectoragendas.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
                @endif
                <div class="panel panel-default">
                    <div class="panel-header-form">
                        <h3 class="panel-titles">@lang('words.Create')</h3>                    
                    </div>
                    <div class="panel-body black-letter">
                        {!! Form::open(['route' => ['inspectoragendas.store']]) !!}
                            @include('inspector_agenda._form')
                            <!-- Submit Form Button -->                        
                            {!! Form::submit(trans('words.Create'), ['class' => 'btn-body']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>                 
            
        </div>
    </div>
@endsection