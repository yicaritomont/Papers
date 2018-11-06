@extends('layouts.app')

@section('title', trans('words.ManageUsers'))

@section('content')
<div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('header.Profile') {{ $user->name }}</h2>                    
                    <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12 profile_left">
                        <div class="picture_perfil">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <img class="img-responsive avatar-view" src="{{asset($user->picture)}}" alt="Avatar" title="Change the avatar">
                            </div>
                            <h3>{{ $user->name }}</h3>
                        </div>
                        

                        <ul class="picture_perfil list-unstyled user_data">
                            <li><i class="fa fa-map-marker user-profile-icon"></i> {{ $user->email }}</li>                           
                        </ul>

                        <a href="{{ route('perfiles.edit',$user->id)  }}" class="btn btn-success"><i class="fa fa-edit m-right-xs"></i> @lang('words.Edit') @lang('header.Profile')</a>
                        <a href="{{ route('perfiles.show',$user->id)  }}" class="btn btn-info"><i class="fa fa-key m-right-xs"></i> @lang('words.Edit') @lang('words.Password')</a>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection