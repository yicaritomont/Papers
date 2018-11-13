@extends('layouts.app')

@section('title', trans_choice('words.Contract', 2))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ trans_choice('words.Contract', 2) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_posts')
                <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Date')</th>
                <th>{{trans_choice('words.Company',1)}}</th>
                <th>@lang('words.Client')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_contracts', 'delete_contracts')
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
                dataTableObject.language = {url:'{{ asset("dataTable/lang/Spanish.json") }}'};           
            }

            @can('edit_contracts', 'delete_contracts')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Contract', 'entity' => 'contracts', 'identificador' => 'id', 'relations' => 'company,client,client.user']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'date'},
                    {data: 'company.name'},
                    {data: 'client.user.name'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Contract', 'entity' => 'contracts']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'date'},
                    {data: 'company.name'},
                    {data: 'client.user.name'},
                    {data: 'created_at'},
                ];
            @endcan

            var table = $('.dataTable').DataTable(dataTableObject);                
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection