@extends('layouts.app')

@section('title', trans_choice('words.Profession',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">
            <h3 class="modal-title"> {{ trans_choice('words.Profession', 2) }}</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_professions')
                <a href="{{ route('professions.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-hover dataTable nowrap" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.CreatedAt')</th>
                <th>@lang('words.UpdatedAt')</th>
                @can('edit_professions','delete_professions')
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
                {data: 'created_at'},
                {data: 'updated_at'},
            ];

            @can('edit_professions','delete_professions')
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Profession', 'entity' => 'professions', 'identificador' => 'id', 'relations' => 'none']) }}";
                
                columns.push({data: 'actions', className: 'text-center'},)
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-2, -3])];     
            @else
                dataTableObject.ajax = "{{ route('datatable', ['model' => 'Profession']) }}";
                dataTableObject.columns = columns;
                dataTableObject.columnDefs = [setDataTable([-1, -2])];
            @endcan

            var table = $('.dataTable').DataTable(dataTableObject);
            new $.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
