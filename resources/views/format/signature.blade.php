@extends('layouts.app')

@section('title', Lang::get('words.ViewSignanute') )

@section('content')

<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
    <div id="buttonsPanel">
        <a href="{{ route('formats.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    </div>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.ViewSignanute')</h3>
        </div>
        <div class="panel-body w-50 p-3">
            <div class="row" style="height: 500px;">
                <iframe src="data:application/pdf;base64,{{$contents}}" height="100%" width="100%"></iframe>                        
            </div>
            <div class="col-xs-12">            
                <span class="btn btn-primary btn-body" id="boton_sellar_formato" info="sello" value="{{$id}}"><i class="glyphicon glyphicon-tag"></i> {!! trans('words.SignaSelloTiempo') !!}</span>
            </div>
        </div>
    </div>
</div>
@endsection

