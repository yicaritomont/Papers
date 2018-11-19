@extends('layouts.app')

@section('title', trans('words.Create').' '.trans_choice('words.Preformato', 1).', ')

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <a href="{{ route('preformatos.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Create')</h3>                    
                </div>
                <div class="panel-body black-letter">
                    {!! Form::open(['route' => ['preformatos.store'] ]) !!}
                        @include('preformato._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Create'), ['class' => 'btn-body']) !!}
                    {!! Form::close() !!}
                </div>
            </div>                 
        
    </div>
@endsection