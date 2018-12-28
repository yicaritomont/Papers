@extends('layouts.app')

@section('title', trans_choice('words.Headquarters', 1))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> @choice('words.Headquarters', 2) </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('edit_headquarters')
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
                <th>@lang('words.Address')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                <th class="text-center">@lang('words.Actions')</th>
            </tr>
            </thead>
        </table>

    </div>

    <div class="row" style="padding: 10px 0">
        <div class="col-sm-12">
            <div id="map" style="//border: 1px solid #d3d3d3;"></div>
        </div>
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
                {data: 'address'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'actions', className: 'text-center wCellActions', orderable: false},
            ];

            dataTableObject.columnDefs = [formatDateTable([-2, -3])];

            dataTableObject.columnDefs.push({
                //En la columna 6 (actions) se agregan nuevo boton
                targets: 6,
                render: function(data, type, row){

                    btn = '<button type="button" class="btn-delete btn btn-xs btn-primary" title="@lang("words.Whatch")" onclick="VerMapa('+row.id+')">\
                        <i class="fa fa-eye"></i>\
                    </button>';

                    return data + btn;
                }
            });

            @can('edit_headquarters', 'delete_headquarters')
                @if(isset($clientAuth))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Headquarters', 'whereHas' => 'client,slug,'.$clientAuth->slug, 'entity' => 'headquarters', 'identificador' => 'slug', 'relations' => 'cities,client,client.user']) }}"};
                @else    
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Headquarters', 'whereHas' => 'none', 'entity' => 'headquarters', 'identificador' => 'slug', 'relations' => 'cities,client,client.user']) }}"};
                @endif
            @else
                @if(isset($clientAuth))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Headquarters', 'whereHas' => 'client,slug,'.$clientAuth->slug, 'relations' => 'cities,client,client.user']) }}"};
                @else    
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Headquarters', 'whereHas' => 'none', 'relations' => 'cities,client,client.user']) }}"};
                @endif
            @endcan
            
            dataTableObject.ajax.type = 'POST';
            dataTableObject.ajax.data = {_token: window.Laravel.csrfToken};
            dataTableObject.columns = columns;

            table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>

    @include ('shared._mapIndex')
@endsection