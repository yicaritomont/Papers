@extends('layouts.app')

@section('title', trans_choice('words.Format',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Format',$result->count()) }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_formats')
                <a href="{{ route('formats.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
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
                @can('edit_formats','delete_formats')
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

                    @can('edit_formats','delete_formats')
                        <td class="text-center">
                            @include('shared._actions', [
                                'entity' => 'formats',
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
