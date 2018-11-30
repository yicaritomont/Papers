@extends('layouts.app')

@section('title', trans_choice('words.InspectorAgenda', 1))

@section('content')
    <div class="row">
        <div class="col-md-5">

            @if(isset($id))
                <h3 class="modal-title">{{ trans_choice('words.InspectorAgenda', $result->count()) }} {{ $result[0]->inspector['name'] }}  </h3>
            @else
                <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.InspectorAgenda', $result->count()) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            <a class="btn btn-info btn-sm" href="{{ route('inspectoragendas.index') }}">@lang('words.calendarView')</a>
            @can('add_inspectoragendas')
                <a href="{{ route('inspectoragendas.create', ['view'=>'list']) }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
            @if(isset($id))
                <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
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
                <th>@choice('words.Headquarters', 1)</th>
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
                        @can('add_inspectionappointments')
                            <form action="{{ route('inspectionappointments.create.post')  }}" method="POST">
                                @csrf
                                <input type="hidden" name="inspector_id" value="{{ $item->inspector['id'] }}">
                                <input type="hidden" name="date" value="{{ $item->date }}">
                                <input type="hidden" name="start_time" value="{{ $item->start_time }}">
                                <input type="hidden" name="end_time" value="{{ $item->end_time }}">
                                <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Añadir cita</button>
                            </form>
                        @endcan
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