@extends('layouts.app')

@section('title', trans('words.ManageUsers'))

@section('content')
    <div class="row">
        <div class="col-md-5">            

            @if(isset($companies))
                <h3 class="modal-title">{{ str_plural(trans('words.User'), 2) }} @lang('words.Of') {{ $companies[0]->name }}  </h3>
            @else
                <h3 class="modal-title"> {{ str_plural(trans('words.User'), 2) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @if(isset($companies))
                <a href="{{ route('companies.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
            @can('add_users')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Email')</th>
                <th>@lang('words.Roles')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_users', 'delete_users')
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

            var dataTableObject = {
                responsive: true,
                serverSide: true,
            };

            //Se valida el idioma
            if(window.Laravel.language == 'es'){
                dataTableObject.language = {url:'{{ asset("js/lib/dataTable/Spanish.json") }}'};           
            }

            @can('edit_users', 'delete_users')
                @if(isset($companies))
                    dataTableObject.ajax = "{{ route('users.companyTable', $companies[0]->slug) }}";
                @else
                    dataTableObject.ajax = "{{ route('datatable', ['model' => 'User', 'entity' => 'users', 'identificador' => 'id', 'relations' => 'roles']) }}";
                @endif

                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'roles'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
                dataTableObject.columnDefs = [{
                    //En la columna 3 (roles) se recorre el areglo y luego se muestran los nombres de cada posición
                    targets: 3,
                    createdCell: function(td, cellData, rowData, row, col){
                        $(td).html('');
                        cellData.forEach(function(element){
                            $(td).append(element.name+' ');
                        });
                    }
                }]
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'User', 'entity' => 'users']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'email'},
                    {data: 'created_at'},
                ];
            @endcan          

            var table = $('.dataTable').DataTable(dataTableObject);                       
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection