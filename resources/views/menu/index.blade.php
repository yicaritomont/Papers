@extends('layouts.app')

@section('title', trans('words.Create').' '.trans('words.ManageMenu'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result }} {{ str_plural(trans('words.ManageMenu'), $result) }} </h3>
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

            var dataTableObject = {
                responsive: true,
                serverSide: true,
            };

            //Se valida el idioma
            if(window.Laravel.language == 'es'){
                dataTableObject.language = {url:'{{ asset("dataTable/lang/Spanish.json") }}'};           
            }

            @can('edit_menus', 'delete_menus')
                
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Menu', 'entity' => 'menus', 'identificador' => 'id', 'relations' => 'menu,modulo']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'url'},
                    {data: 'menu.name'},
                    {data: 'modulo.name'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Menu', 'entity' => 'menus']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'created_at'},
                ];
            @endcan
            
            var table = $('.dataTable').DataTable(dataTableObject);                  
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection