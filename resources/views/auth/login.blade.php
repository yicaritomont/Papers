@extends('layouts.app')

@section('content')
<div class="content-login">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 ">
            <div class="panel panel-default ">                
                <div class="panel-body background-login" align="center">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div>
                            <h2>@lang('words.Login')</h2>
                        </div>
                        <div class="title-space-login">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" align="center">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input id="email" type="email" class="input-login" name="email" autocomplete="off" placeholder="@lang('words.E-Mail')" value="{{ old('email') }}" required autofocus>                                    
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} title-space-login" align="center">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input id="password" type="password" class="input-login" name="password" required placeholder="@lang('words.Password')">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>                                

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <button type="submit" class="btn-login">
                                        @lang('words.SingIn')
                                    </button>                                    
                                </div>                                    
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        @lang('words.Forgot') @lang('words.Your') @lang('words.Password')?
                                    </a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('words.Remember')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


            <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
            <script>
                /* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
                particlesJS.load('particles-js', 'js/particlesjs-config.json', function() {
                    console.log('callback - particles.js config loaded');
                });
            </script>
@endsection
