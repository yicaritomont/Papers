@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ str_plural(trans('words.User'), $users->count()) }} @lang('words.Of') {{ $company->name }}  </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            <a href="{{ route('companies.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Email')</th>
                <th>@lang('words.Roles')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_users', 'delete_users')
                <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @forelse($users as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->roles->implode('name', ', ') }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>

                    @can('edit_users')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'users',
                            'id' => $item->id
                        ])
                    </td>
                    @endcan
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios en la empresa</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- <div class="text-center">
            {{ $result->links() }}
        </div> --}}
    </div>

@endsection