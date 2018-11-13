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
                        <div class="x_title">
                            <h2>@lang('header.Profile') {{ $usuario->name }}</h2>                    
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12 profile_left">
                                <div class="picture_perfil">
                                    <div id="crop-avatar">
                                        <!-- Current avatar -->
                                       
                                        <img class="img-responsive avatar-view" src="{{asset($usuario->picture)}}" alt="Avatar" title="Change the avatar">
                                    </div>
                                    <h3>{{ strtoupper($usuario->name) }}</h3>
                                   {{$infoInspector->status}}
                                </div>
                                <table class="table table-responsive">
                                    <tr>
                                        <td>
                                            <ul class="list-unstyled user_data">
                                                <li><i class="fa fa-map-marker user-profile-icon"></i> {{$infoInspector->addres}}</li>                                   
                                                <li><i class="fa fa-phone"></i> {{$infoInspector->phone}}</li>                                   
                                                <li><i class="fa fa-envelope"></i> {{$usuario->email}}</li>
                                                <li><i class="fa fa-university"></i> {{$infoInspector->profession->name}} - {{$infoInspector->inspectorType->name}}</li>
                                            </ul>
                                            <ul class="list-unstyled">
                                            @foreach($infoInspector->companies as $companias)
                                                <li><i class="fa fa-users"></i> {{$companias->name}}</li>
                                            @endforeach                                
                                            </ul>
                                        </td>
                                        <td>
                                            {{\App\Http\Controllers\InspectorController::qrInfoInspector($infoInspector->id)}}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                 
        
    </div>
@endsection