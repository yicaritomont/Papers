@extends('layouts.app')

@section('title', trans('words.Headquarters'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.Headquarters'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_posts')
                <a href="{{ route('headquarters.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Client')</th>
                <th>@lang('words.City')</th>
                <th>@lang('words.Address')</th>
                <th>@lang('words.Status')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_headquarters', 'delete_headquarters')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->client->user->name }}</td>
                    <td>{{ $item->cities->name }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->status == 1 ? trans('words.Active') : trans('words.Inactive') }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    <td class="text-center">
                        @can('edit_headquarters')
                            <a href="{{ route('headquarters.edit', $item->slug)  }}" class="btn btn-xs btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endcan
                        @can('delete_headquarters')
                        {!! Form::open( ['method' => 'delete', 'url' => route('headquarters.destroy', ['user' => $item->slug]), 'style' => 'display: inline']) !!}                            
                            @if($item->status == 1)
                                <button class="btn  btn-xs btn-success"><span class='glyphicon glyphicon-ok-sign'></span></button>
                            @else
                                <button class="btn  btn-xs btn-danger"><span class='glyphicon glyphicon-remove-sign'></button>
                            @endif    
                        {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {{ $result->links() }}
        </div>
    </div>

@endsection