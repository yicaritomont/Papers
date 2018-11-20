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
                <th>@lang('words.Name')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_formats','delete_formats')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
        </table>

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

            @can('edit_formats','delete_preformats')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Format', 'entity' => 'formats', 'identificador' => 'id']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Format', 'entity' => 'formats']) }}";
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
