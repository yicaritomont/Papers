@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans('words.InspectorAgenda').' '.$inspectorAgenda->name)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-clockpicker.css')}}">
@endsection

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
        <a href="{{ redirect()->getUrlGenerator()->previous() }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        <div class="panel panel-default">
            <div class="panel-header-form">
                <h3 class="panel-titles">@lang('words.Edit') {{ $inspectorAgenda-> name }}</h3>                    
            </div>
            <div class="panel-body black-letter">
                {!! Form::model($inspectorAgenda, ['method' => 'PUT', 'route' => ['inspectoragendas.update',  $inspectorAgenda->slug ]]) !!}
                        @include('inspector_agenda._form')
                        
                        <!-- Submit Form Button -->                           
                        <input class="btn-body" type="submit" value="@lang('words.SaveChanges')">
                {!! Form::close() !!}
            </div>
        </div>                 
    </div>


    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        
                    </div>
                </div>
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