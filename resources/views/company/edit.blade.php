@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Company',1).' - '.$user->name)

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('companies.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') {{ $user->name }}</h3>
                </div>
                <div class="panel-body black-letter">
                    {!! Form::model($company, ['method' => 'PUT', 'route' => ['companies.update',  $company->slug ]]) !!}
                            @include('company._form')

                            <!-- Submit Form Button -->
                            <input class="btn-body" type="submit" value="@lang('words.SaveChanges')">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
