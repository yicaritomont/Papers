@extends('layouts.app')

@section('title', trans_choice('words.Company', 2))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> @choice('words.Company', 2) </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_companies')
                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table id="dataTable" class="table table-bordered table-hover dataTable nowrap">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Address')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.Email')</th>
                <th>@lang('words.Activity')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @if(Gate::check('edit_companies') || Gate::check('delete_companies') || Gate::check('view_users') || Gate::check('view_inspectors'))
                    <th class="text-center">@lang('words.Actions')</th>
                @endif
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
                {data: 'address'},
                {data: 'phone'},
                {data: 'user.email'},
                {data: 'activity'},
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @if(Gate::check('edit_companies') || Gate::check('delete_companies') || Gate::check('view_users') || Gate::check('view_inspectors'))
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Company', 'company' => 'none', 'entity' => 'companies', 'identificador' => 'slug', 'relations' => 'user']) }}";
                
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columnDefs = [setDataTable([-2, -3])];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Company', 'company' => 'none', 'relations' => 'user']) }}";
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endif

            dataTableObject.columns = columns;

            @if(Gate::check('view_users') || Gate::check('view_inspectors'))
                dataTableObject.columnDefs.push({
                    //En la columna 8 (actions) se agregan nuevos botones
                    targets: 8,
                    render: function(data, type, row){
                        var btn =   '<div class="dropdown" style="display:inline-block">\
                                        <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="watchMenu" data-toggle="dropdown">\
                                            <i class="fa fa-eye"></i>\
                                        </button>\
                                        <ul class="dropdown-menu pull-right" aria-labelledby="watchMenu" style="text-align:right">';

                        @can('view_users')
                            btn +=  '<li>\
                                        <a target="_blank" href="'+window.Laravel.url+'/users?id='+row.slug+'">\
                                            @lang("words.Whatch") @lang("words.User")\
                                        </a>\
                                    </li>';
                        @endcan
                        @can('view_inspectors')
                            btn +=  '<li>\
                                        <a target="_blank" href="'+window.Laravel.url+'/inspectors?id='+row.slug+'">\
                                            @lang("words.Whatch") @choice("words.Inspector", 2)\
                                        </a>\
                                    </li>';
                        @endcan

                        btn += '</ul></div>';

                        return data + btn;
                    }
                });
            @endif

            var table = $('.dataTable').DataTable(dataTableObject);
        });
    </script>
@endsection