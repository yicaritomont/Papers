@extends('layouts.app')

@section('title', trans('words.Create').' '.trans_choice('words.Client',1).' - ')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
        <a href="{{ route('clients.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        <div class="panel panel-default">
            <div class="panel-header-form">
                <h3 class="panel-titles">@lang('words.Create') {{ trans_choice('words.Client',1)}}</h3>
            </div>
            <div class="panel-body black-letter">
                {!! Form::open(['route' => ['clients.store']]) !!}
                    @include('client._form')
                    <!-- Submit Form Button -->
                    {!! Form::submit(trans('words.Create'), ['class' => 'btn-body']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
