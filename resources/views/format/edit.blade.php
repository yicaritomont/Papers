@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Format',1).', ')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <a href="{{ route('formats.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') {{ $formato->name  }}</h3>
                </div>
                <div class="panel-body black-letter">
                    <div id="contenedorHtml">
                        @include('format._form')
                    </div>
                        {!! Form::model($formato,['method' => 'PUT', 'route' => [ 'formats.update', $formato->id], 'id' => 'form_expediction']) !!}
                        <input type="hidden" name="format_expediction" id="format_expediction">

<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
    <a href="{{ route('formats.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.Edit') {{ $formato->name  }}</h3>
        </div>
        <div class="panel-body black-letter">
                {!! Form::model($formato,['method' => 'PUT', 'route' => [ 'formats.update', $formato->id], 'id' => 'form_expediction']) !!}
                    <div id="contenedorHtml">
                        @include('format._form')
                    </div>
                <input type="hidden" name="format_expediction" id="format_expediction">

            <span class="btn btn-primary btn-body" id="boton_guardar_html">{!! trans('words.SaveChanges') !!}</span>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
