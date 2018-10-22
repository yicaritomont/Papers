@extends('layouts.app')

@section('title', trans('words.Permission'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.Permission'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_permissions')
                <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
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
                
                @can('delete_permissions')
                    <th class="text-center">Actions</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>                    
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    @can('delete_permissions')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'permissions',
                            'id' => $item->name
                        ])
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>

        
    </div>
    <div class="text-center">
            {{ $result->links() }}
        </div>

@endsection