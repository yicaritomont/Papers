@extends('layouts.app')

@section('title', Lang::get('words.ViewSignanute') )

@section('content')
<a href="{{ route('formats.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
    <div class="jumbotron">
        <h1>{!! trans('words.Info') !!}</h1>
        <p>{!! trans('words.SignaInfo') !!} </p>
        <span class="btn btn-primary btn-body" id="boton_informacion_sellos" info="info"><i class="glyphicon glyphicon-tag"></i> {!! trans('words.Info') !!}</span>
        <div id="showInfo"></div>
    </div>
</div>
@endsection

