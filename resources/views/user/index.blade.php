@extends('layouts.app')

@section('title', trans('words.ManageUsers'))

@section('content')
    <div class="row">
        <div class="col-md-5">            

            @if(isset($companies))
                <h3 class="modal-title">{{ str_plural(trans('words.User'), $result->count()) }} @lang('words.Of') {{ $companies[0]->name }}  </h3>
            @else
                <h3 class="modal-title">{{ $result->total() }} {{ str_plural('User', $result->count()) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @if(isset($companies))
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
                    <td>
                        @can('edit_users')
                            <a href="{{ route('users.edit', $item->id)  }}" class="btn btn-xs btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endcan
                        @can('delete_users')
                        {!! Form::open( ['method' => 'delete', 'url' => route('users.destroy', ['user' => $item->id]), 'style' => 'display: inline']) !!}                            
                            @if($item->status == 1)
                                <button class="btn  btn-xs btn-success"><span class='glyphicon glyphicon-ok-sign'></span></button>
                            @else
                                <button class="btn  btn-xs btn-danger"><span class='glyphicon glyphicon-remove-sign'></button>
                            @endif    
                        {!! Form::close() !!}
                        @endcan
                    </td>  
                    <!--@can('edit_users')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'users',
                            'id' => $item->id
                        ])
                    </td>
                    @endcan !-->
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            @if(!isset($companies))
                {{ $result->links() }}
            @endif
        </div>
    </div>

@endsection