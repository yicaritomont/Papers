@extends('layouts.app')

@section('title', trans_choice('words.Inspectionappointment', 1))

@section('content')

    <div class="msgAlert"></div>    
    
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-5">
                            @if(isset($id))
                                <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Inspectionappointment', $result->count()) }}  @lang('words.Of') {{ $result[0]->inspector['name'] }}  </h3>
                            @else
                                <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Inspectionappointment', $result->count()) }} </h3>
                            @endif
                        </div>
                        <div class="col-md-7 text-right">
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

            <ul style="list-style-type:none">
                @foreach($appointment_states as $state)
                    <li><span class="glyphicon glyphicon-bookmark" style="color:{{ $state->color }}"></span> {{$state->name}}</li>
                @endforeach
            </ul>  
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

                    {!! Form::open(['route' => ['inspectionappointments.store'], 'class' => 'formCalendar', 'id' => 'formCreateAppointmet', 'data-modal'=>'#modalCreate']) !!}
                        @include('inspection_appointment._form')

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
                        <button data-toggle="#showCita" class="btn btn-primary showCalendar">@lang("words.Watch")</button>
                        <form method="POST" id="deleteAppointment" class="formCalendar" data-modal="#modalEditDel" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmModal('#deleteAppointment', '{{trans('words.DeleteMessage')}}', 'warning')" class="btn btn-danger" id="">@lang('words.Delete')</button>
                        </form>
                        <div class="btns"></div>
                    </div>

                    {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar formSlide', 'id' => 'editAppointment', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}
                        {{-- @include('inspection_appointment._form') --}}

                        <div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
                            {!! Form::label('inspector_id', trans_choice("words.Inspector", 1)) !!}
                            {!! Form::select('inspector_id',$inspectors, isset($agenda) ? $agenda['inspector_id'] : null, ['class' => 'input-body', 'placeholder'=>trans('words.ChooseOption')]) !!}
                            @if ($errors->has('inspector_id')) <p class="help-block">{{ $errors->first('inspector_id') }}</p> @endif
                        </div>

                        <div class="form-group @if ($errors->has('start_date')) has-error @endif">
                            {!! Form::label('start_date', trans('words.StartDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                        </div>

                        <div class="form-group @if ($errors->has('end_date')) has-error @endif">
                            {!! Form::label('end_date', trans('words.EndDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
                        </div>

                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Edit'), ['class' => 'btn btn-primary btn-block']) !!}
                    {!! Form::close() !!}

                    {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar formSlide', 'id' => 'completeAppointment', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}
                        <div class="form-group @if ($errors->has('start_date')) has-error @endif">
                            {!! Form::label('start_date', trans('words.StartDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                        </div>

                        <div class="form-group @if ($errors->has('end_date')) has-error @endif">
                            {!! Form::label('end_date', trans('words.EndDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
                        </div>
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Complete'), ['class' => 'btn btn-primary btn-block']) !!}
                    {!! Form::close() !!}

                    <div class="formSlide" id="showCita" style="display:none"></div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('words.Close')</button>
                </div>
            </div>
      
        </div>
    </div>

    <input type="hidden" id="url" value="{{route('inspectionappointments.index')}}">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="selectOption" value="{{trans('words.ChooseOption')}}">
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>

    <script type="text/javascript">

        var date1 = '2018-11-22 15:05:03';

        console.log('Fecha 1: ' + date1);

        var date2 = date1.replace(' ', 'T')+'Z';
        console.log('Fecha 2:' + date2);
        var jun = moment.tz(date2, moment.tz.guess());

        console.log(jun.format());
        console.log(jun.format('MMMM Do YYYY, h:mm:ss a'));
        console.log(jun.fromNow());

        console.log(moment.tz.guess());
    
        //Se define un objeto que contenga las caracteristicas particulares de cada calendario y luego se definen
        var calendarObj = {};
        calendarObj.customButtons = null;
        calendarObj.events = $('#url').val()+'/events';
        calendarObj.eventClick = function(event)
        {
            //Resetar y setear el action el formulario de completar
            $('#completeAppointment')[0].reset();
            $('#completeAppointment').attr('action', $('#url').val()+'/'+event.id+'/complete');

            //Cambiar el action del formulario
            $('#deleteAppointment').attr('action', $('#url').val()+'/'+event.id);
            $('.showCalendar').attr('data-route', $('#url').val()+'/'+event.id);
            
            if(event.appointment_states_id == 1){
                $('.btns').html('<button class="btn btn-info btn-form-slide" data-toggle="#completeAppointment">@lang("words.Complete")</button>');
            }else if(event.appointment_states_id == 2){
                $('.btns').html('<button data-toggle="#editAppointment" class="btn btn-primary editCalendar" data-route="'+$('#url').val()+'/'+event.id+'/edit'+'">@lang("words.Edit")</button>');
            }else{
                $('.btns').html('');
            }

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
            if(start != ed){
                limpiarForm(start[0], ed, '#formCreateAppointmet', 'estimated_', '#inspection_subtype_id');
                $('#modalCreate').modal('show');
            }
        };
        calendarObj.dayClick = function(date, jsEvent, view)
        {
            limpiarForm(date.format(), null, '#formCreateAppointmet', 'estimated_', '#inspection_subtype_id');
            $('#modalCreate').modal('show');
        };
        calendarObj.eventDrop = function(calEvent, delta, revertFunc)
        {
            var end = calEvent.end.format().split('T');

            $('#editAppointment').attr('action', $('#url').val()+'/'+calEvent.id);
            $('#modalEditDel #start_date').val(calEvent.start.format());
            $('#modalEditDel #end_date').val(end[0]);
            $('#modalEditDel #inspector_id').val(calEvent.inspector_id);

            confirmModal('#editAppointment', '{{trans('words.UpdateMessage')}}', 'question', revertFunc);
        };


        @can('add_inspectionappointments')
            calendarObj.customButtons = {
                createButton: {
                    text: '{{trans('words.Create')}}',
                    click: function() {
                        $('.msgError').html('');
                        $('#modalCreate #date').removeAttr("disabled");
                        $('#formCreateAppointmet')[0].reset();
                        $('#modalCreate').modal('show');
                    }
                }
            };
        @endcan

        //Se llama la función que inicializará el calendario de acuerdo al objeto enviado
        calendar(calendarObj);

    </script>   
@endsection