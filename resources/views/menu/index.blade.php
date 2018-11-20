@extends('layouts.app')

@section('title', trans('words.Create').' '.trans('words.ManageMenu'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ str_plural(trans('words.ManageMenu'), 2) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_menus')
                <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Url')</th>
                <th>@lang('words.Menu')</th>
                <th>@lang('words.Modules')</th>
                <th>Created At</th>
                @can('edit_menus', 'delete_menus')
                    <th class="text-center">Actions</th>
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
                {data: 'url'},
                {data: 'menu.name'},
                {data: 'modulo.name'},
                {data: 'created_at'},
            ];

            @can('edit_menus', 'delete_menus')
                
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Menu', 'entity' => 'menus', 'identificador' => 'id', 'relations' => 'menu,modulo']) }}";
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Menu', 'relations' => 'menu,modulo']) }}";
                dataTableObject.columns = columns;
            @endcan
            
            var table = $('.dataTable').DataTable(dataTableObject);                  
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection