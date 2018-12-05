@extends('layouts.app')

@section('title', trans_choice('words.Headquarters', 1))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> @choice('words.Headquarters', 2) </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_posts')
                <a href="{{ route('headquarters.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@choice('words.Client', 1)</th>
                <th>@lang('words.City')</th>
                <th>@lang('words.Address')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_headquarters', 'delete_headquarters')
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
                {data: 'client.user.name'},
                {data: 'cities.name'},
                {data: 'address'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_headquarters', 'delete_headquarters')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Headquarters', 'company' => 'none', 'entity' => 'headquarters', 'identificador' => 'slug', 'relations' => 'cities,client,client.user']) }}";
                columns.push({data: 'actions', className: 'text-center w1em'},)
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Headquarters', 'company' => 'none', 'relations' => 'cities,client,client.user']) }}";
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan
            
            dataTableObject.columns = columns;

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection