@extends('layouts.app')

@section('title', trans('words.Permission'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ str_plural(trans('words.Permission'), 2) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_permissions')
                <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table id="dataTable" class="table table-bordered table-hover dataTable nowrap">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>            
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('delete_permissions')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>   
        </table>  
    </div>

    <input type="hidden" name="permisos" value="{{ app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete_permissions') }}">
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

            @can('delete_permissions')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Permission', 'entity' => 'permissions', 'identificador' => 'name', 'relations' => 'none']) }}";
 
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Permission']) }}";
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan

            var table = $('.dataTable').DataTable(dataTableObject);                   
            // new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection