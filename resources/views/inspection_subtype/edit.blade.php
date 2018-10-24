@extends('layouts.app')

@section('title', 'Edit Inspection Type ' . $type->name)

@section('content')

  <div class="row">
        <div class="col-md-5">
            <h3>@lang('words.Edit') {{ $type->name }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            <a href="{{ route('appoinment_states.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        </div>
    </div>

    <div class="wrapapper wrapapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content">
                    {!! Form::model($type,['method' => 'PUT', 'route' => [ 'appoinmentstates.update', $type->id]]) !!}
                        @include('appoinment_state._form')

                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary'])!!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection