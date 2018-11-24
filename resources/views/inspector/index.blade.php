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
            @if(isset($companies))
                <a href="{{ route('companies.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
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
                <th>{{trans_choice('words.Company',2)}}</th>
                <th>{{trans_choice('words.Profession',2)}}</th>
                <th>{{trans_choice('words.InspectorType',2)}}</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_inspectors','delete_inspectors')
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
                {data: 'user.name'},
                {data: 'identification'},
                {data: 'phone'},
                {data: 'addres'},
                {data: 'user.email'},
                {data: 'companies'},
                {data: 'profession.name'},
                {data: 'inspector_type.name'},
                {data: 'created_at'},
            ];

            //Columnas a ser modificadas
            var columnDefs = [
                {
                    //En la columna 6 (companies) se recorre el areglo y luego se muestran los nombres de cada posición
                    targets: 6,
                    createdCell: function(td, cellData, rowData, row, col){
                        $(td).html('');
                        cellData.forEach(function(element){
                            $(td).append(element.user.name+' ');
                        });
                    }
                }
            ]

            @can('edit_inspectors','delete_inspectors')
                @if(isset($companies))
                    dataTableObject.ajax = "{{ route('inspectors.companyTable', $companies->slug) }}";
                @else
                    dataTableObject.ajax = "{{ route('datatable', ['model' => 'Inspector', 'entity' => 'inspectors', 'identificador' => 'id', 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}";
                @endif

                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;

                columnDefs.push(
                    {
                        //En la columna 10 (actions) se agrega el boton de ver inspector
                        targets: 10,
                        createdCell: function(td, cellData, rowData, row, col){
                            $(td).append('<a target="_blank" href="'+window.Laravel.url+'/validateInspector/'+rowData.id+'" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> @lang("words.Whatch") {{trans_choice("words.Inspector", 2)}}</a>');

                        }
                    },
                )
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Inspector', 'relations' => 'companies,profession,inspectorType,user,companies.user']) }}";
                dataTableObject.columns = columns;
            @endcan

            dataTableObject.columnDefs = columnDefs;

            var table = $('.dataTable').DataTable(dataTableObject);
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
