@extends('layouts.app')

@section('title', 'Inspector')

@section('content')
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel">
        {!! Form::open(['method' => 'post']) !!}
       <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="roleModalLabel">Inspector</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Inspector Name']) !!}
                        @if ($errors->has('name')) <p class="help-block">{{ $error->first('name') }}</p> @endif
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {!! Form::submit('submit', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
       </div>
    </div>
       <div class="row">
            <div class="col-md-5">
                <h3 class="modal-title">{{ $result->total() }}@lang('words.Inspectors')</h3>
            </div>
            <div class="col-md-7 page-action text-right">
                @can('add_inspectors')
                    <a href="#" class="btn btn-sm btn-succes pull-right" data-toggle="modal" data-target="#roleModal"><i class="glyphicon glyphicon-plus"></i>New </a>
                @endcan
            </div>
       </div> 
@endsection