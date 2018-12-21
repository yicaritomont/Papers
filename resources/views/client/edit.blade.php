@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Client', 1).' '.$user->name)

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('clients.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') {{ $user-> name }}</h3>
                </div>
                <div class="panel-body black-letter">
                    {!! Form::model($client, ['method' => 'PUT', 'route' => ['clients.update',  $client->slug ]]) !!}
                            @include('client._form')
                            <!-- Submit Form Button -->
                            <input class="btn-body" type="submit" value="@lang('words.SaveChanges')">
                    {!! Form::close() !!}
                </div>
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
