@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="row">
        <div class="col-md-5">

            @if(isset($cpy))
                <h3 class="modal-title">{{ str_plural(trans('words.User'), $result->count()) }} @lang('words.Of') {{ $cpy[0]->name }}  </h3>
            @else
                <h3 class="modal-title">{{ $result->total() }} {{ str_plural('User', $result->count()) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @if(isset($cpy))
                <a href="{{ route('companies.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
            @can('add_users')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
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
            @foreach($result as $item)
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
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            @if(!isset($cpy))
                {{ $result->links() }}
            @endif
        </div>
    </div>

@endsection