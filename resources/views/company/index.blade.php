@extends('layouts.app')

@section('title', trans('words.Company'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.Company'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_companies')
                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Address')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.E-Mail')</th>
                <th>@lang('words.Status')</th>
                <th>@lang('words.Activity')</th>
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
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->status == 1 ? trans('words.Active') : trans('words.Inactive') }}</td>
                    <td>{{ $item->activity }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    @can('edit_companies', 'delete_companies')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'companies',
                            'id' => $item->slug
                        ])
                        <a href="{{ route('companies.show', [str_singular('companies') => $item->slug])  }}" class="btn btn-xs btn-primary">
                        @can('view_users')
                            <i class="fa fa-eye"></i> @lang('words.Whatch') @lang('words.User')</a>
                        @endcan
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