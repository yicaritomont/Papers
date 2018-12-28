@extends('layouts.app')

@section('title', trans_choice('words.Contract', 2))

@section('content')
    <div class="row">
        <div class="col-md-5">
            @if(isset($companies))
                <h3 class="modal-title">@choice('words.Contract', 2) @lang('words.Of') {{ $companies->user->name }}</h3>
            @else
                <h3 class="modal-title"> @choice('words.Contract', 2)</h3>
            @endif
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
                <th>@choice('words.Company',1)</th>
                <th>@choice('words.Client', 1)</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th> 
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

            //Se definen las columnas (Sin actions)
            var columns = [
                {data: 'id'},
                {data: 'name'},
                {data: 'date'},
                {data: 'company.user.name'},
                {data: 'client.user.name'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_contracts', 'delete_contracts')
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Contract', 'whereHas' => 'company,slug,'.$companies->slug, 'entity' => 'contracts', 'identificador' => 'id', 'relations' => 'company,client,client.user,company.user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Contract', 'whereHas' => 'none', 'entity' => 'contracts', 'identificador' => 'id', 'relations' => 'company,client,client.user,company.user']) }}"};
                @endif

                
                columns.push({data: 'actions', className: 'text-center wCellActions', orderable: false},)
                dataTableObject.columnDefs = [formatDateTable([-2, -3])];
            @else
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Contract', 'whereHas' => 'company,slug,'.$companies->slug, 'relations' => 'company,client,client.user,company.user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Contract', 'whereHas' => 'none', 'relations' => 'company,client,client.user,company.user']) }}"};
                @endif

                dataTableObject.columnDefs = [formatDateTable([-1, -2])];
            @endcan

            dataTableObject.ajax.type = 'POST';
            dataTableObject.ajax.data = {_token: window.Laravel.csrfToken};
            dataTableObject.columns = columns;

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection