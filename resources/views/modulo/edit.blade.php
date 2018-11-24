@extends('layouts.app')

@section('title', trans('words.Edit'))

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('modulos.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit')</h3>                    
                </div>
                <div class="panel-body black-letter">
                    {!! Form::model($modulo, ['method' => 'PUT', 'route' => ['modulos.update',  $modulo->id ] ]) !!}
                        @include('modulo._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.SaveChanges'), ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>                 
        </div>    
    </div>
@endsection