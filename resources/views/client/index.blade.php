@extends('layouts.app')

@section('title', trans('words.Client'))

@section('content')

    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ str_plural(trans('words.Client'), 2) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_clients')
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Identification')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.Email')</th>
                <th>@lang('words.CellPhone')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_clients', 'delete_clients')
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
                {data: 'identification'},
                {data: 'user.name'},
                {data: 'phone'},
                {data: 'user.email'},
                {data: 'cell_phone'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_clients', 'delete_clients')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Client', 'entity' => 'clients', 'identificador' => 'slug', 'relations' => 'user']) }}";
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Client', 'relations' => 'user']) }}";
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan
               
            var table = $('.dataTable').DataTable(dataTableObject);              
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
