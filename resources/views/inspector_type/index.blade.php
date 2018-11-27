@extends('layouts.app')

@section('title', trans_choice('words.InspectorType', 2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ trans_choice('words.InspectorType', 2) }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_inspectortypes')
                <a href="{{ route('inspectortypes.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>{{ trans_choice('words.InspectionSubtype',1) }}</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_inspectortypes','delete_inspectortypes')
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
                {data: 'inspection_subtypes.name'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            

            @can('edit_inspectortypes','delete_inspectortypes')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'InspectorType', 'entity' => 'inspectortypes', 'identificador' => 'id', 'relations' => 'inspection_subtypes,inspection_subtypes.inspection_types']) }}";
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'InspectorType', 'relations' => 'inspection_subtypes,inspection_subtypes.inspection_types']) }}";
                dataTableObject.columns = columns;
                dataTableObject..columnDefs = [setDataTable([-1, -2])];
            @endcan

            dataTableObject.columnDefs.push(
                {
                    //En la columna 2 (inspection_subtypes) se arega el tipo de inspección
                    targets: 2,
                    render: function(data, type, row){
                        return data + ' - '+row.inspection_subtypes.inspection_types.name;
                    }
                },
            );
            
            var table = $('.dataTable').DataTable(dataTableObject);              
            // new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection