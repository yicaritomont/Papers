@extends('layouts.app')

@section('title', 'Card')

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">            
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles"></h3>                    
                </div>
                <div class="panel-body black-letter">
                    <div class="x_panel">                        
                        <div class="x_content">
                            {!! Form::open(['route' => ['saveReadInspector'] ]) !!}
                                <div class="col-md-12 col-sm-12 col-xs-12 profile_left">
                                    <div class="picture_perfil">
                                        <h3>{{ strtoupper($usuario->name) }}</h3>
                                    </div>
                                    <table class="table table-responsive">
                                        <tr>
                                            <td>
                                                <img class="img-responsive avatar-view" src="{{asset($usuario->picture)}}" alt="Avatar" title="Change the avatar">
                                            </td>
                                            <td>
                                                <ul class="list-unstyled user_data">
                                                    <li><i class="fa fa-map-marker user-profile-icon"></i> {{$infoInspector->addres}}</li>                                   
                                                    <li><i class="fa fa-phone"></i> {{$infoInspector->phone}}</li>                                            </ul>
                                                <ul class="list-unstyled">
                                                @foreach($infoInspector->companies as $companias)
                                                    <li><i class="fa fa-users"></i> {{$companias->name}}</li>
                                                @endforeach                                
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div id="map"></div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>                 
    </div>
@endsection

@section('scripts')

@include ('shared._scriptUbicacionGoogle')

@endsection