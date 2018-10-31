@extends('layouts.app')

@section('title', trans('words.Create'))

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-clockpicker.css')}}">
@endsection

@section('content')
    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('inspectoragendas.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
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
@endsection

@section('scripts')

    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.es.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-clockpicker.js')}}"></script>
    
    <script type="text/javascript">

        $(document).ready(function(){

            //Campo hora
            $('.clockpicker').clockpicker();

            //Campo fecha
            $('.input-group.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                orientation: "bottom auto",
                @if(app()->getLocale()=='es')
                language: "es",
                @endif
            });
        })
    </script>
@endsection