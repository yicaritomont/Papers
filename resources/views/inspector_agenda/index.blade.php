@extends('layouts.app')

@section('title', trans_choice('words.InspectorAgenda', 1))

@section('content')

    <div class="msgAlert"></div>
        
    <div class="row">
        <div class="col-xs-12 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">              
                    <div class="row">
                        <div class="col-md-8">
                            @if(isset($id))
                                <h3 class="modal-title">{{ trans_choice('words.InspectorAgenda', $result->count()) }} {{ $result[0]->inspector['name'] }}  </h3>
                            @else
                                <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.InspectorAgenda', $result->count()) }} </h3>
                            @endif
                        </div>
                        <div class="col-md-4 text-right">
                            {{-- <a class="btn btn-info" href="{{ route('inspectoragendas.view') }}">@lang('words.tableView')</a> --}}
                            @if(isset($id))
                                <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCreate" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">@lang('words.Create')</h3>
                </div>
                <div class="modal-body">
                    <div class="msgError"></div>
                    {!! Form::open(['route' => ['inspectoragendas.store.ajax'], 'class' => 'formCalendar', 'id' => 'formCreateAgenda', 'data-modal'=>'#modalCreate']) !!}
                        @include('inspector_agenda._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Create'), ['class' => 'btn-body']) !!}
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('words.Close')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar y Eliminar -->
    <div class="modal fade" id="modalEditDel" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">@lang('words.WhatYouLike')</h3>
                </div>
                <div class="modal-body">
                    <div class="msgError"></div>

                    <div class="content-btn">
                        <button data-toggle="#showAgenda" class="btn btn-primary showCalendar">@lang("words.Watch")</button>
                        {{-- <button class="btn btn-primary detailsCalendar" data-toggle="#showAgenda">@lang("words.Watch")</button> --}}
                        <form method="POST" id="deleteAgenda" class="formCalendar" data-modal="#modalEditDel" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmModal('#deleteAgenda', '{{trans('words.DeleteMessage')}}', 'warning')" class="btn btn-danger" id="">@lang('words.Delete')</button>
                        </form>
                        <button data-toggle="#editAgenda" class="btn btn-primary editCalendar">@lang("words.Edit")</button>
                        {{-- <button class="btn btn-primary" data-toggle="#editAgenda">@lang("words.Edit")</button> --}}
                    </div>

                    {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar formSlide', 'id' => 'editAgenda', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}
                        @include('inspector_agenda._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Edit'), ['class' => 'btn btn-primary btn-block']) !!}
                    {!! Form::close() !!}

                    <div class="formSlide" id="showAgenda" style="display:none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center active" colspan="2" style="font-size:2em">@lang('words.AgendaInformation')</th>
                                </tr>
                            </thead>
                            <tr>
                                <th>@lang('words.StartDate'): </th>
                                <td id="cell-start_date"></td>
                            </tr>
                            <tr>
                                <th>@lang('words.EndDate'): </th>
                                <td id="cell-end_date"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.Inspector', 1): </th>
                                <td id="cell-inspector"></td>
                            </tr>
                            <tr>
                                <th>@lang('words.Country'): </th>
                                <td id="cell-country"></td>
                            </tr>
                            <tr>
                                <th>@lang('words.City'): </th>
                                <td id="cell-city"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="display:inline" type="button" class="btn btn-default" data-dismiss="modal">@lang('words.Close')</button>
                </div>
            </div>
      
        </div>
    </div>

    <input type="hidden" id="url" value="{{ route('inspectoragendas.index') }}">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="selectOption" value="{{trans('words.ChooseOption')}}">
@endsection

@section('scripts')

    <script type="text/javascript" >

        //Se define un objeto que contenga las caracteristicas particulares de cada calendario y luego se definen
        var calendarObj = {};
        calendarObj.customButtons = null;
        calendarObj.events = $('#url').val()+'/events';
        calendarObj.eventClick = function(event)
        {
            //Cambiar el action del formulario
            $('#deleteAgenda').attr('action', $('#url').val()+'/ajax/'+event.slug);
            $('.showCalendar').attr('data-route', $('#url').val()+'/'+event.slug);
            $('.editCalendar').attr('data-route', $('#url').val()+'/'+event.slug+'/edit');

            //Se limpia las alertas
            $('.msgError').html('');

            //Ocultar los formularios desplegables
            $(".formSlide").hide();

            $('#modalEditDel').modal('show');
        };
        calendarObj.select = function(startDate, endDate, jsEvent, view)
        {
            //Separar en fecha[0] y hora[1]
            var start = startDate.format().split('T');

            //Como al seleccionar los días la fecha final al día le agrega uno de más, hay que hacer la conversión
            var ed = new Date(endDate.format());  
            ed = ed.getFullYear()+'-'+ ("0" + (ed.getMonth() + 1)).slice(-2) +'-'+("0" + ed.getDate()).slice(-2);
            
            //Validar se se secciono un rango de dias, de lo contrario pase al evento dayClick
            if(start != ed)
            {
                limpiarForm(start[0], ed, '#formCreateAgenda', '', '.city_id');
                $('#modalCreate').modal('show');
            }
        };
        calendarObj.dayClick = function(date, jsEvent, view)
        {
            limpiarForm(date.format(), null, '#formCreateAgenda', '', '.city_id');
            $('#modalCreate').modal('show');
        };
        calendarObj.eventDrop = function(calEvent, delta, revertFunc)
        {
            var end = calEvent.end.format().split('T');

            $('#editAgenda').attr('action', $('#url').val()+'/ajax/'+calEvent.slug);
            $('#modalEditDel #start_date').val(calEvent.start.format());
            $('#modalEditDel #end_date').val(end[0]);
            $('#modalEditDel #inspector_id').val(calEvent.inspector_id);

            confirmModal('#editAgenda', '{{trans('words.UpdateMessage')}}', 'question', revertFunc);
        };


        @can('add_inspectoragendas')
            calendarObj.customButtons = {
                createButton: {
                    text: '{{trans('words.Create')}}',
                    click: function() {
                        $('.msgError').html('');
                        $('#modalCreate #date').removeAttr("disabled");
                        $('#formCreateAgenda')[0].reset();
                        $('#modalCreate').modal('show');
                    }
                }
            };
        @endcan

        //Se llama la función que inicializará el calendario de acuerdo al objeto enviado
        calendar(calendarObj);

    </script>    
@endsection