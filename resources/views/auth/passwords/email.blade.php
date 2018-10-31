@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                              
                <div class="panel-body background-login">
                    <div>
                        <h2>@lang('words.Reset') @lang('words.Password')</h2>
                    </div> 
                    <div class="title-space-login">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!--<form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">-->
                       
                        {!! Form::open(['action' => 'RemindersController@postRemind', 'class' => 'form-signin']) !!}

                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">@lang('words.E-Mail')</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="input-login" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn-login">
                                        @lang('words.SendPasswordResetLink')
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
