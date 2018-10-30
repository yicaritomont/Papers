{{--

* password_remind.blade.php
* Ruta:              /laravel-skeleton/app/views/auth/password_remind.blade.php
* Fecha Creación:    Feb/2015
*
* Vista para recordar contraseña.
*
* @author           Jorge Leonardo Ramirez Montoya <jleoramirezm@hotmail.com>
* @copyright        2015 Jorge Leonardo Ramirez Montoya
* @license          GPL 2 or later
* @version          2015
*

--}}

@extends('main_layout')
@section('content')

    
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Recordar Contraseña</h1>
            <div class="account-wall">
                <!-- <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt=""> -->
                
                {{ Form::open(array('action' => 'RemindersController@postRemind', 'class' => 'form-signin') ) }}
                
                    {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Email', 'required', 'autofocus')) }}
                    <br>
                    {{ Form::submit('Recordar Contraseña', ['class' => 'btn btn-lg btn-primary btn-block']) }}

                {{ Form::close() }}
            </div>
            
        </div>
    </div>
   
@stop
