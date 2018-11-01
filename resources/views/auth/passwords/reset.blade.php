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
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('words.E-Mail')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="input-login" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('words.Password')</label>

                            <div class="col-md-6">
                                <input id="password_update" type="password" class="input-login" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">@lang('words.Confirm') @lang('words.Password')</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="input-login" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p id="div_info_lengthPwd" ></p>
                        <p id="div_info_lengthNumber"></p>
                        <p id="div_info_lengthLower"></p>
                        <p id="div_info_lengthUpper"></p>
                        <p id="div_info_beforePass"></p>
                        <p id="div_info_keyWordPass"></p>
                        <p id="div_info_confirmPass"></p>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="changePassword" class="btn-login">
                                    @lang('words.SaveChanges')
                                </button>
                            </div>
                        </div>
                    </form>
                       
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
