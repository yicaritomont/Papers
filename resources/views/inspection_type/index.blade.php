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
            ];

            @can('edit_inspectiontypes','delete_inspectiontypes')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'InspectionType', 'entity' => 'inspectiontypes', 'identificador' => 'id', 'relations' => 'none']) }}";
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'InspectionType']) }}";
                dataTableObject.columns = columns;
            @endcan
            
            var table = $('.dataTable').DataTable(dataTableObject);                    
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection