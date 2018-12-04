@extends('layouts.app')

@section('title', trans('words.ManageUsers'))

@section('content')
    <div class="row">
        <div class="col-md-5">            

            @if(isset($companies))
                <h3 class="modal-title">{{ str_plural(trans('words.User'), 2) }} @lang('words.Of') {{ $companies->user->name }}  </h3>
            @else
                <h3 class="modal-title"> {{ str_plural(trans('words.User'), 2) }} </h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
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
                <th>@choice('words.Company', 2)</th>
                <th>@lang('words.Roles')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th> 
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

            //Se definen las columnas (Sin actions)
            var columns = [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'companies'},
                {data: 'roles'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_users', 'delete_users')
                @if(isset($companies))
                    dataTableObject.ajax = "{{ route('users.companyTable', $companies->slug) }}";
                @else
                    dataTableObject.ajax = "{{ route('datatable', ['model' => 'User', 'entity' => 'users', 'identificador' => 'id', 'relations' => 'roles,companies,companies.user']) }}";
                @endif

                columns.push({data: 'actions', className: 'text-center w1em'},)
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'User', 'relations' => 'roles,companies,companies.user']) }}";
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan

            dataTableObject.columnDefs.push(
                {
                    //En la columna 4 (roles) se recorre el areglo y luego se muestran los nombres de cada posición
                    targets: 4,
                    render: function(data, type, row){
                        var res = '';
                        data.forEach(function(element){
                            res += element.name+' ';
                        });
                        return res;
                    }
                },{
                    //En la columna 3 (companies) se recorre el areglo y luego se muestran los nombres de cada posición
                    targets: 3,
                    render: function(data, type, row){
                        var res = '';
                        data.forEach(function(element){
                            res += element.user.name+' ';
                        });
                        return res;
                    }
                }
            );       

            var table = $('.dataTable').DataTable(dataTableObject);                       
            // new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection