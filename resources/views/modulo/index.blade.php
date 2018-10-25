@extends('layouts.app')

@section('title', trans('words.Create').' '.trans('words.ManageModulo'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.ManageModulo'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_modulos')
                <a href="{{ route('modulos.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>                
                <th>@lang('words.CreatedAt')</th>               
                @can('edit_modulos', 'delete_modulos')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>                    
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>                    
                    <td>
                        @can('edit_modulos')
                            <a href="{{ route('modulos.edit', $item->id)  }}" class="btn btn-xs btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endcan
                        @can('delete_modulos')
                        {!! Form::open( ['method' => 'delete', 'url' => route('modulos.destroy', ['user' => $item->id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("Are your sure wanted to delete it?")']) !!}                            
                            @if($item->state == 1)
                                <button class="btn  btn-xs btn-success"><span class='glyphicon glyphicon-ok-sign'></span></button>
                            @else
                                <button class="btn  btn-xs btn-danger"><span class='glyphicon glyphicon-remove-sign'></button>
                            @endif    
                        {!! Form::close() !!}
                        @endcan
                    </td>                

                   <!-- @can('edit_modulos', 'delete_modulos')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'modulos',
                            'id' => $item->id
                        ])
                    </td>
                    @endcan-->
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {{ $result->links() }}
        </div>
    </div>

@endsection