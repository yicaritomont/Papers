@extends('layouts.app')

@section('title', trans_choice('words.InspectionType',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ trans_choice('words.InspectionType', 2) }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_inspectiontypes')
                <a href="{{ route('inspectiontypes.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_inspectiontypes','delete_inspectiontypes')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
        </table>
    </div>

@endsection

@section('scripts')
    <script>  
        
        $(document).ready(function() {

            //Se definen las columnas (Sin actions)
            var columns = [
                {data: 'id'},
                {data: 'name'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_inspectiontypes','delete_inspectiontypes')
                dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'InspectionType', 'whereHas' => 'none', 'entity' => 'inspectiontypes', 'identificador' => 'id', 'relations' => 'none']) }}"};
                columns.push({data: 'actions', className: 'text-center wCellActions', orderable: false},);
                dataTableObject.columnDefs = [formatDateTable([-2, -3])];
            @else
                dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'InspectionType', 'whereHas' => 'none']) }}"};
                dataTableObject.columnDefs = [formatDateTable([-1, -2])];
            @endcan
            
            dataTableObject.ajax.type = 'POST';
            dataTableObject.ajax.data = {_token: window.Laravel.csrfToken};
            dataTableObject.columns = columns;

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection