@extends('layouts.app')

@section('title', trans_choice('words.Company', 2))

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result }} {{ trans_choice('words.Company',$result)  }} </h3>
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
                @can('edit_companies', 'delete_companies')
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

            @can('edit_companies', 'delete_companies')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Company', 'entity' => 'companies', 'identificador' => 'slug']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'address'},
                    {data: 'phone'},
                    {data: 'email'},
                    {data: 'activity'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
                dataTableObject.columnDefs = [{
                    //En la columna 7 (actions) se agregan nuevos botones
                    targets: 7,
                    createdCell: function(td, cellData, rowData, row, col){
                        console.log(rowData.slug);
                        var btn = '';
                        @can('view_users')
                            btn += '<a href="'+window.Laravel.url+'/users/company/'+rowData.slug+'" class="btn btn-xs btn-primary">';
                            btn += '<i class="fa fa-eye"></i>@lang("words.Whatch") @lang("words.User")</a>';
                            
                        @endcan
                        @can('view_inspectors')
                            btn += '<a href="'+window.Laravel.url+'/inspectors/company/'+rowData.slug+'" class="btn btn-xs btn-primary">';
                            btn += '<i class="fa fa-eye"></i>@lang("words.Whatch") @lang("words.Inspectors")</a>';
                                     
                        @endcan
                        console.log('Botones '+btn)
                        $(td).append(btn);
                    }
                }]
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Company', 'entity' => 'companies']) }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'address'},
                    {data: 'phone'},
                    {data: 'email'},
                    {data: 'activity'},
                    {data: 'created_at'},
                ];
            @endcan      

            var table = $('.dataTable').DataTable(dataTableObject);                
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection