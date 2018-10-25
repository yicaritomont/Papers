@extends('layouts.app')

@section('title', trans('words.Edit'))

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('users.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') @lang('words.Password')</h3>                    
                </div>
                <div class="panel-body black-letter">
                    {!! Form::open(['route' => ['perfiles.destroy', $user->id] ,'enctype' => "multipart/form-data" ]) !!}
                        <!-- password Form Input -->
                        <div class="form-group @if ($errors->has('password')) has-error @endif">
                            <label for="password">@lang('words.Password')</label>
                            {!! Form::password('password', ['class' => 'input-body']) !!}   
                            @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                        </div>
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.SaveChanges'), ['class' => 'btn-body']) !!}
                    {!! Form::close() !!}
                </div>
            </div>                 
        
    </div>
@endsection