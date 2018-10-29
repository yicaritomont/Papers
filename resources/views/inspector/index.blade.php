@extends('layouts.app')

@section('title', trans_choice('words.Inspectors',2).', ')

@section('content')
    <div class="row">
        <div class="col-md-5">

            @if(isset($companyObj))
                <h3 class="modal-title">{{ str_plural(trans('words.Inspector'), $result->count()) }} @lang('words.Of') {{ $companyObj[0]->name }}  </h3>
            @else
                <h3 class="modal-title">{{ $result->total() }} {{ str_plural('inspector',$result->count()) }}</h3>
            @endif
        </div>
        <div class="col-md-7 page-action text-right">
            @if(isset($companyObj))
                <a href="{{ route('companies.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
            @endif
            @can('add_inspectors')
                <a href="{{ route('inspectors.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i>@lang('words.Create')</a>
            @endcan
        </div>
    </div>

    <div class="result-set">
        <table class="table table-bordered table-striped table-hover" id="data-table">
            <thead>
            <tr>
                <th>@lang('words.Id')</th>
                <th>@lang('words.Name')</th>
                <th>@lang('words.Identification')</th>
                <th>@lang('words.Phone')</th>
                <th>@lang('words.Addres')</th>
                <th>@lang('words.Email')</th>
                <th>{{trans_choice('words.Profession',2)}}</th>
                <th>{{trans_choice('words.InspectorType',2)}}</th>
                <th>@lang('words.CreatedAt')</th>
                @can('edit_inspectors','delete_inspectors')
                    <th class="text-center">@lang('words.Actions')</th>
                @endcan
            </tr>
            </thead>
            <tbody>
                @foreach($result as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->identification}}</td>
                    <td>{{ $item->phone}}</td>
                    <td>{{ $item->addres}}</td>
                    <td>{{ $item->email}}</td>
                     <td>{{ $item->profession['name']}}</td>
                    <td>{{ $item->inspectorType['name']}}</td>
                    <td>{{ $item->created_at->toFormattedDateString() }}</td>

                    @can('edit_inspectors','delete_inspectors')
                        <td class="text-center">
                            @include('shared._actions', [
                                'entity' => 'inspectors',
                                'id' => $item->id
                            ])
                            @can('view_inspectoragendas')
                                <a href="{{ route('inspectoragendas.inspector', $item->id)  }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-eye"></i> @lang('words.Whatch') @lang('words.InspectorAgenda')
                                </a>
                            @endcan
                        </td>
                    @endcan
                    
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            @if(!isset($companyObj))
                {{ $result->links() }}
            @endif
        </div>
    </div>

@endsection