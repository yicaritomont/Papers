@extends('layouts.app')

@section('title', 'Edit Inspector ' . $inspector->name)

@section('content')

  <div class="row">
        <div class="col-md-5">
            <h3>@lang('words.Edit') {{ $inspector->name }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            <a href="{{ route('inspectors.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        </div>
    </div>

    <div class="wrapapper wrapapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content">
                    {!! Form::model($inspector,['method' => 'PUT', 'route' => [ 'inspectors.update', $inspector->id]]) !!}
                        @include('inspector._form')

                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary'])!!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection