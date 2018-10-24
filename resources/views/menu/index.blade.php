@extends('layouts.app')

@section('title', trans('words.Create').' '.trans('words.ManageMenu'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.ManageMenu'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_menus')
                <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Url')</th>
                <th>@lang('words.Menu')</th>
                <th>@lang('words.Modules')</th>
                <th>Created At</th>
                @can('edit_menus', 'delete_menus')
                    <th class="text-center">Actions</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->url }}</td>
                    <td>{{ $item->menu['name'] }}</td>
                    <td>{{ $item->modulo['name'] }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    @can('edit_menus', 'delete_menus')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'menus',
                            'id' => $item->id
                        ])
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {{ $result->links() }}
        </div>
    </div>

@endsection