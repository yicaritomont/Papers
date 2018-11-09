@extends('layouts.app')

@section('title', trans('words.Client'))

@section('content')
    
    {{--  {{dd(auth()->user()->roles->pluck('name', 'id'))}} --}}

    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title">{{ $result->count() }} {{ str_plural(trans('words.Client'), $result->count()) }} </h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_clients')
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover dataTable" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Identification')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.Email')</th>
                <th>@lang('words.CellPhone')</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_posts', 'delete_posts')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            
        </table>

        {{-- <div class="text-center">
            {{ $result->links() }}
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script>  
        //Se valida el idioma
        $(document).ready(function() {

            var dataTableObject = {
                responsive: true,
                serverSide: true,
            };

            if(window.Laravel.language == 'es'){
                dataTableObject.language = {url:'{{ asset("dataTable/lang/Spanish.json") }}'};           
            }

            @can('edit_posts', 'delete_posts')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Client', 'entity' => 'clients', 'identificador' => 'slug', 'relations' => 'user']) }}";
                //dataTableObject.ajax = "{{ url('datatable/Permission/permissions/name') }}";
                dataTableObject.columns = [
                    {data: 'id'},
                    {data: 'identification'},
                    {data: 'user.name'},
                    {data: 'phone'},
                    {data: 'user.email'},
                    {data: 'cell_phone'},
                    {data: 'created_at'},
                    {data: 'actions', className: 'text-center'},
                ];
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Permission', 'entity' => 'permissions']) }}";
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