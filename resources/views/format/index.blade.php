@extends('layouts.app')

@section('title', trans_choice('words.Format',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Format',$result->count()) }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_formats')
                <a href="{{ route('formats.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover dataTable" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@choice('words.Preformato',2)</th>
                <th>@choice('words.Company',2)</th>
                <th>@choice('words.Client', 2)</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_formats','delete_formats')
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

          var columns = [
            {data: 'id'},
            {data: 'preformato.name'},
            {data: 'company.user.name'},
            {data: 'client.user.name'},
            {data: 'created_at'},
            {data: 'updated_at'},
          ]

            var dataTableObject = {
                responsive: true,
                serverSide: true,
            };

            @can('edit_formats','delete_preformats')
                dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Format', 'company' => 'none', 'entity' => 'formats', 'identificador' => 'id', 'relations' => 'preformato,company.user,client.user']) }}"};
                columns.push({data: 'actions', className: 'text-center w1em'},)
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Format', 'company' => 'none', 'relations' => 'preformato,company.user,client.user']) }}"};
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan

            dataTableObject.ajax.type = 'POST';
            dataTableObject.ajax.data = {_token: window.Laravel.csrfToken};
            dataTableObject.columns = columns;

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection
