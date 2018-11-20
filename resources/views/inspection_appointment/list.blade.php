@extends('layouts.app')

@section('title', 'Inspection Appointment')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ str_plural('InspectionAppointment',$result->count()) }}</h3>           
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_inspectionappointments')
                <a href="{{ route('inspectionappointments.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@choice('words.Inspector', 1)</th>
                <th>@lang('words.InspectionType')</th>
                <th>@lang('words.Date')</th>
                <th>@lang('words.StartTime')</th>
                <th>@lang('words.EndTime')</th>
                <th>@lang('words.Status')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_inspectionappointments','delete_inspectionappointments')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->inspector['name']}}</td>
                    <td>{{ $item->inspectionType['name']}}</td>
                    <td>{{ $item->date}}</td>
                    <td>{{ $item->start_time}}</td>
                    <td>{{ $item->end_time}}</td>
                    <td>{{ $item->appointmentState['name']}}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>

                    @can('edit_inspectionappointments','delete_inspectionappointments')
                        <td class="text-center">
                            @include('shared._actions', [
                                'entity' => 'inspectionappointments',
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

@section('scripts')
    <script>
        console.log('XD');
        $('#dateInspectionAppointment').change(function(){
            console.log('Cambio');
        });
    </script>
@endsection
