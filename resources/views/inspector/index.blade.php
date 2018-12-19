@extends('layouts.app')

@section('title', trans_choice('words.Inspector',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">

            @if(isset($companies))
                <h3 class="modal-title">{{ trans_choice('words.Inspector', 2) }} @lang('words.Of') {{ $companies->user->name }}</h3>
            @else
                <h3 class="modal-title"> {{ trans_choice('words.Inspector', 2) }}</h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_inspectors')
                <a href="{{ route('inspectors.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Identification')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.Addres')</th>
                <th>@lang('words.Email')</th>
                <th>@choice('words.Company',2)</th>
                <th>@choice('words.Profession',2)</th>
                <th>@choice('words.InspectorType',2)</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                {{-- @if(Gate::check('edit_inspectors') || Gate::check('delete_inspectors') || Gate::check('view_inspectoragendas') || Gate::check('view_inspectionappointments'))              --}}
                {{-- @can('edit_inspectors','delete_inspectors') --}}
                    <th class="text-center">@lang('words.Actions')</th>
                {{-- @endcan --}}
                {{-- @endif --}}
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
                {data: 'user.name'},
                {data: 'identification'},
                {data: 'phone'},
                {data: 'addres'},
                {data: 'user.email'},
                {data: 'companies'},
                {data: 'profession.name'},
                {data: 'inspector_type.name'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            //Columnas a ser modificadas
            dataTableObject.columnDefs = [
                {
                    //En la columna 6 (companies) se recorre el areglo y luego se muestran los nombres de cada posición
                    targets: 6,
                    render: function(data, type, row){
                        var res = [];
                        data.forEach(function(elem){
                            res.push(elem.user.name);
                        });

                        return res.join(', ');
                        /*return data.map(function(elem){
                            return elem.user.name;
                        }).join(", ");*/
                    }
                }
            ];

            @if(Gate::check('edit_inspectors') || Gate::check('delete_inspectors') || Gate::check('view_inspectoragendas') || Gate::check('view_inspectionappointments'))
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Inspector', 'company' => 'user.companies,'.$companies->slug, 'entity' => 'inspectors', 'identificador' => 'id', 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Inspector', 'company' => 'none', 'entity' => 'inspectors', 'identificador' => 'id', 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}"};
                @endif
            @else
                @if(isset($companies))
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Inspector', 'company' => 'user.companies,'.$companies->slug, 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}"};
                @else
                    dataTableObject.ajax = {url: "{{ route('datatable', ['model' => 'Inspector', 'company' => 'none', 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}"};
                @endif
            @endif

            columns.push({data: 'actions', className: 'text-center w1em'});
            dataTableObject.columns = columns;

            dataTableObject.columnDefs.push(
                {
                    //En la columna 11 (actions) se agrega el boton de ver inspector
                    targets: 11,
                    render: function(data, type, row){
                        var btn =   '<div class="dropdown" style="display:inline-block">\
                                        <button class="btn btn-xs btn-primary dropdown-toggle" type="button" title="Ver" id="watchMenu" data-toggle="dropdown">\
                                            <i class="fa fa-eye"></i>\
                                            \
                                        </button>\
                                        <ul class="dropdown-menu pull-right" aria-labelledby="watchMenu" style="text-align:right">\
                                            <li><a target="_blank" href="'+window.Laravel.url+'/validateInspector/'+row.id+'">@lang("words.Whatch") {{trans_choice("words.Inspector", 2)}}</a></li>';

                        @can('view_inspectoragendas')
                            btn +=  '<li>\
                                        <a target="_blank" href="'+window.Laravel.url+'/inspectoragendas?id='+row.id+'">\
                                            @lang("words.Whatch") @choice("words.InspectorAgenda", 2)\
                                        </a>\
                                    </li>';
                        @endcan

                        @can('view_inspectionappointments')
                            btn +=  '<li>\
                                        <a target="_blank" href="'+window.Laravel.url+'/inspectionappointments?id='+row.id+'">\
                                            @lang("words.Whatch") @choice("words.Inspectionappointment", 2)\
                                        </a>\
                                    </li>';
                        @endcan

                        btn += '</ul></div>';

                        return data + btn;
                    }
                },
                setDataTable([-2, -3])
            );

            dataTableObject.ajax.type = 'POST';
            dataTableObject.ajax.data = {_token: window.Laravel.csrfToken};

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection
