@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Preformato',1).' - "'.$preformato->name.'", ')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <a href="{{ route('preformatos.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') {{ $preformato->name  }}</h3>
                </div>
                <div class="panel-body black-letter">
                    {!! Form::model($preformato,['method' => 'PUT', 'route' => [ 'preformatos.update', $preformato->id]]) !!}
                        @include('preformato._form')

                        {!! Form::submit(trans('words.SaveChanges'), ['class' => 'btn btn-primary'])!!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
