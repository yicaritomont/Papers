@extends('layouts.app')

@section('title', trans('words.Support'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.Support'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_clients')
                <a href="{{ route('support.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Date')</th>
                <th>@lang('words.Route')</th>
                <th>@lang('words.Status')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Slug')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_posts', 'delete_posts')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->route }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    @can('edit_clients', 'delete_clients')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'supports',
                            'id' => $item->slug
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