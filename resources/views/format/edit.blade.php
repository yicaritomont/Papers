@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Format',1).', ')

@section('content')
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
                <input type="hidden" name="state" id="state" value="1">
          <div  >
            <div class="col-xs-4" style="display:{!! $state_format !!};">
                <a href="{{ route('formats.supports', [str_singular('formats') => $formato->id])  }}" class="btn btn-primary btn-body">
                {!! trans('words.upload_sopports') !!}</a>
            </div>
            <div class="col-xs-4" style="display:{!! $state_firma !!};">
              <span class="btn btn-primary btn-body" id="boton_firmar_formato" info="firma" value="{{$formato->id}}">{!! trans('words.SignFormat') !!}</span>
            </div>
            <div class="col-xs-4" style="display:{!! $state_format !!};">
              <span class="btn btn-primary btn-body" id="boton_guardar_html">{!! trans('words.SaveChanges') !!}</span>
            </div>
          <div>
            {!! Form::close() !!}
        </div>
    </div>
  </div>
@endsection
