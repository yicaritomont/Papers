@extends('layouts.app')

@section('title', trans('words.Edit').' '.trans_choice('words.Contract', 1).' '.$contract->name)

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-3">
            <a href="{{ route('contracts.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            
            <div class="panel panel-default">
                <div class="panel-header-form">
                    <h3 class="panel-titles">@lang('words.Edit') {{ $contract-> name }}</h3>                    
                </div>
                <div class="panel-body black-letter">
                    {!! Form::model($contract, ['method' => 'PUT', 'route' => ['contracts.update',  $contract->id ]]) !!}
                            @include('contract._form')
                            
                            <!-- Submit Form Button -->                           
                            <input class="btn-body" type="submit" value="@lang('words.SaveChanges')">
                    {!! Form::close() !!}
                </div>
            </div>                 
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('#company_id').trigger('change', {{$contract->client_id}});
    </script>
@endsection