@extends('layouts.app')

@section('title', trans('words.Headquarters'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Contract', $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_posts')
                <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
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
                <th>{{trans_choice('words.Company',1)}}</th>
                <th>@lang('words.Client')</th>
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
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->company->name }}</td>
                    <td>{{ $item->client->user->name }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    <td class="text-center">
                        @can('edit_contracts')
                            <a href="{{ route('contracts.edit', $item->id)  }}" class="btn btn-xs btn-info">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endcan
                        @can('delete_contracts')
                        {!! Form::open( ['method' => 'delete', 'url' => route('contracts.destroy', ['user' => $item->id]), 'style' => 'display: inline']) !!}                            
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