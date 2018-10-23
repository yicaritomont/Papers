@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans('words.Headquarters').' '.$headquarters->name)

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
        <a href="{{ route('headquarters.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        <div class="panel panel-default">
            <div class="panel-header-form">
                <h3 class="panel-titles">@lang('words.Edit') {{ $headquarters-> name }}</h3>                    
            </div>
            <div class="panel-body black-letter">
                {!! Form::model($headquarters, ['method' => 'PUT', 'route' => ['headquarters.update',  $headquarters->slug ]]) !!}
                        @include('headquarters._form')
                        
                        <!-- Status of Headquarters Form Select -->
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            {{-- {!! Form::label('status', trans('words.Status')) !!}
                            <select name="status" id="status" class="form-control">
                                <option value="0" {{ $headquarters->status === 0 ? 'selected' : '' }}>Inactivo</option>
                                <option value="1" {{ $headquarters->status === 1 ? 'selected' : '' }}>Activo</option>
                            </select> --}}
                            <div>
                                <input type="radio" id="active" name="status" value="1" {{ $headquarters->status === 1 ? 'checked' : '' }} />
                                <label for="active">@lang('words.Active')</label>
                            </div>
                            <div>
                                <input type="radio" id="inactive" name="status" value="0" {{ $headquarters->status === 0 ? 'checked' : '' }} />
                                <label for="inactive">@lang('words.Inactive')</label>
                            </div>
                            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                        </div>
                        
                        <!-- Submit Form Button -->                           
                        <input class="btn-body" type="submit" value="@lang('words.SaveChanges')">
                {!! Form::close() !!}
            </div>
        </div>                 
    </div>


    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-md-5">
            <h3>@lang('words.Edit')</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            <a href="{{ route('clients.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        {!! Form::model($client, ['method' => 'PUT', 'route' => ['clients.update',  $client->id ] ]) !!}
                            @include('client._form')
                            <!-- Submit Form Button -->
                            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection