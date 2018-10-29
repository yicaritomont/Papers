@extends('layouts.app')

@section('title', trans('words.InspectorAgenda'))

@section('content')
    <div class="row">
        <div class="col-md-5">

            @if(isset($inspector))
                <h3 class="modal-title">{{ str_plural(trans('words.InspectorAgenda'), $result->count()) }} @lang('words.Of') {{ $inspector[0]->name }}  </h3>
            @else
                <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.InspectorAgenda'), $result->count()) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @if(isset($inspector))
                <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
            @can('add_inspectoragendas')
                <a href="{{ route('inspectoragendas.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Date')</th>
                <th>@lang('words.StartTime')</th>
                <th>@lang('words.EndTime')</th>
                <th>@lang('words.Inspector')</th>
                <th>@lang('words.Headquarters')</th>
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
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->start_time }}</td>
                    <td>{{ $item->end_time }}</td>
                    <td>{{ $item->inspector['name'] }}</td>
                    <td>{{ $item->headquarters['name'] }}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>
                    @can('edit_inspectoragendas', 'delete_inspectoragendas')
                    <td class="text-center">
                        @include('shared._actions', [
                            'entity' => 'inspectoragendas',
                            'id' => $item->slug
                        ])
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            @if(!isset($inspector))
                {{ $result->links() }}
            @endif
        </div>
    </div>

@endsection