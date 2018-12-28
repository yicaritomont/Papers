@extends('layouts.app')

@section('title', trans_choice('words.Client', 1))

@section('content')

    <div class="row">
        <div class="col-md-5">
            @if(isset($companies))
                <h3 class="modal-title">{{ trans_choice('words.Client', 2) }} @lang('words.Of') {{ $companies->user->name }}</h3>
            @else
                <h3 class="modal-title">{{ trans_choice('words.Client', 2) }} </h3>
            @endif
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
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Client', 'whereHas' => 'user.companies,slug,'.$companies->slug, 'entity' => 'clients', 'identificador' => 'slug', 'relations' => 'user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Client', 'whereHas' => 'none', 'entity' => 'clients', 'identificador' => 'slug', 'relations' => 'user']) }}"};
                @endif

                columns.push({data: 'actions', className: 'text-center wCellActions', orderable: false},)
                dataTableObject.columnDefs = [formatDateTable([-2, -3])];
            @else
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Client', 'whereHas' => 'user.companies,slug,'.$companies->slug, 'relations' => 'user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Client', 'whereHas' => 'none', 'relations' => 'user']) }}"};
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
