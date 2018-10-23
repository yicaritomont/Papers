@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans('words.Company').' '.$company->name)

@section('content')

    <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
        <a href="{{ route('companies.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        <div class="panel panel-default">
            <div class="panel-header-form">
                <h3 class="panel-titles">@lang('words.Edit') {{ $company-> name }}</h3>                    
            </div>
            <div class="panel-body black-letter">
                {!! Form::model($company, ['method' => 'PUT', 'route' => ['companies.update',  $company->slug ]]) !!}
                        @include('company._form')

                        <!-- Status of Company Form Select -->
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <div>
                                <input type="radio" id="active" name="status" value="1" {{ $company->status  === 1 ? 'checked' : '' }} />
                                <label for="active">@lang('words.Active')</label>
                            </div>
                            <div>
                                <input type="radio" id="inactive" name="status" value="0" {{ $company->status === 0 ? 'checked' : '' }} />
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
@endsection