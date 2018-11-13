@extends('layouts.app')

@section('title', trans('words.Headquarters'))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ str_plural(trans('words.Headquarters'), 2) }} </h3>
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
                <th>@lang('words.Client')</th>
                <th>@lang('words.City')</th>
                <th>@lang('words.Address')</th>
                <th>@lang('words.CreatedAt')</th>
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

            var dataTableObject = {
                responsive: true,
                serverSide: true,
            };

            //Se valida el idioma
            if(window.Laravel.language == 'es'){
                dataTableObject.language = {url:'{{ asset("dataTable/lang/Spanish.json") }}'};           
            }

            @can('edit_headquarters', 'delete_headquarters')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Headquarters', 'entity' => 'headquarters', 'identificador' => 'slug', 'relations' => 'cities,client,client.user']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'client.user.name'},
                    {data: 'cities.name'},
                    {data: 'address'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Headquarters', 'entity' => 'headquarters']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'client.identification'},
                    {data: 'cities.name'},
                    {data: 'address'},
                    {data: 'created_at'},
                ];
            @endcan
            
            var table = $('.dataTable').DataTable(dataTableObject);               
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection