@extends('layouts.app')

@section('title', trans_choice('words.Inspectionappointment', 1))

@section('content')

    <div class="msgAlert"></div>    
    
    <div class="row">
        <div class="col-xs-12 col-lg-8 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-5">
                            @if(isset($inspector))
                                <h3 class="modal-title">{{ count($inspector->inspection_appointments) }} {{ trans_choice('words.Inspectionappointment', count($inspector->inspection_appointments)) }}  @lang('words.Of') {{ $inspector->user->name }}  </h3>
                            @else
                                <h3 class="modal-title">{{ $quantity }} {{ trans_choice('words.Inspectionappointment', $quantity) }} </h3>
                            @endif
                        </div>
                        <div class="col-md-7 text-right">
                            @if(isset($inspector))
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

    @can('add_inspectionappointments')
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
    @endcan

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
                        
                        @can('delete_inspectionappointments')
                            <form method="POST" id="deleteAppointment" class="formCalendar" data-modal="#modalEditDel" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmModal('#deleteAppointment', '{{trans('words.DeleteMessage')}}', 'warning')" class="btn btn-danger" id="">@lang('words.Delete')</button>
                            </form>
                        @endcan

                        <div class="btns"></div>
                    </div>

                    @can('edit_inspectionappointments')
                        {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar formSlide', 'id' => 'editAppointment', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}

                            <div class="form-group">
                                {!! Form::label('start_date', trans('words.StartDate').' - ') !!}
                                {!! Form::label('end_date', trans('words.EndDate')) !!}
                                
                                <div class="input-group date-range-inputs">
                                    <input type="text" class="form-control input-date" name="start_date" id="start_date" autocomplete="off">
                                    <span class="input-group-addon">@lang('words.To')</span>
                                    <input type="text" class="form-control input-date" name="end_date" id="end_date" autocomplete="off">
                                </div>
                                <div class="errors"></div>
                            </div>

                            <div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
                                {!! Form::label('inspector_id', trans_choice("words.Inspector", 1)) !!}
                                {!! Form::select('inspector_id',$inspectors, isset($agenda) ? $agenda['inspector_id'] : null, ['class' => 'input-body', 'placeholder'=>trans('words.ChooseOption')]) !!}
                                <div class="errors"></div>
                            </div>

                            <!-- Submit Form Button -->                        
                            {!! Form::submit(trans('words.Edit'), ['class' => 'btn btn-primary btn-block']) !!}
                        {!! Form::close() !!}

                        {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar formSlide', 'id' => 'completeAppointment', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}

                            <!-- Range Date of Appointment -->
                            <div class="form-group">
                                {!! Form::label('start_date', trans('words.StartDate').' - ') !!}
                                {!! Form::label('end_date', trans('words.EndDate')) !!}
                                
                                <div class="input-group date-range-inputs">
                                    <input type="text" class="form-control input-date" name="start_date" id="start_date" autocomplete="off">
                                    <span class="input-group-addon">@lang('words.To')</span>
                                    <input type="text" class="form-control input-date" name="end_date" id="end_date" autocomplete="off">
                                </div>
                                <div class="errors"></div>
                            </div>
                            <!-- Submit Form Button -->                        
                            {!! Form::submit(trans('words.Complete'), ['class' => 'btn btn-primary btn-block']) !!}
                        {!! Form::close() !!}

                    @endcan

                    <div class="formSlide" id="showCita" style="display:none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center active" colspan="2" style="font-size:2em">@lang('words.AppointmentInformation')</th>
                                </tr>
                            </thead>
                            <tr>
                                <th>@lang('words.RequestDate'): </th>
                                <td id="cell-request_date"></td>
                            </tr>
                            <tr style="display:none">
                                <th>@lang('words.AssignmentDate'): </th>
                                <td id="cell-assignment_date"></td>
                            </tr>
                            <tr style="display:none">
                                <th>@lang('words.EstimatedStartDate'): </th>
                                <td id="cell-estimated_start_date"></td>
                            </tr>
                            <tr style="display:none">
                                <th>@lang('words.EstimatedEndDate'): </th>
                                <td id="cell-estimated_end_date"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.Inspector', 1): </th>
                                <td id="cell-inspector"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.InspectionType', 1): </th>
                                <td id="cell-inspectionType"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.InspectionSubtype', 1): </th>
                                <td id="cell-inspectionSubtype"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.Client', 1): </th>
                                <td id="cell-client"></td>
                            </tr>
                            <tr>
                                <th>@choice('words.Contract', 1): </th>
                                <td id="cell-contract"></td>
                            </tr>
                        </table>
                    </div>

                    @can('add_formats')
                        {!! Form::open(['method' => 'POST', 'class' => 'formCalendar formSlide', 'id' => 'fillFormat', 'data-modal'=>'#modalEditDel', 'style' => 'display:none']) !!}
                            <div class="form-group @if ($errors->has('company')) has-error @endif" id="motrarcompanies">
                                <label for="company_formato">@lang('words.Company')</label>
                                {!! Form::select('company_id',$companies, isset($user) ? $user->companies->pluck('id')->toArray() : null, ['class' => 'input-body','id' => 'company_formato', 'placeholder' => trans('words.ChooseOption')]) !!}
                                @if ($errors->has('company')) <p class="help-block">{{ $errors->first('company')}}</p> @endif
                            </div>
                            <div class="form-group @if ($errors->has('client')) has-error @endif">
                                <label for="cliente_formato">@choice('words.Client', 1)</label>
                                <div  id="contenedor_client">
                                {!! Form::select('client_id',$clients, null, ['class' => 'input-body','id' => 'cliente_formato', 'placeholder' => trans('words.ChooseOption')]) !!}
                                @if ($errors->has('client')) <p class="help-block">{{ $errors->first('client')}}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('preformat')) has-error @endif" id="contenedor_preformat">
                                <label for="format_preformato">@lang('words.Preformato')</label>
                                {!! Form::select('preformat_id',$preformats, null, ['class' => 'input-body','id' => 'format_preformato', 'placeholder' => trans('words.ChooseOption')]) !!}
                                @if ($errors->has('preformat')) <p class="help-block">{{ $errors->first('preformat')}}</p> @endif
                            </div>                     
                            {!! Form::submit(trans('words.Complete'), ['class' => 'btn btn-primary btn-block']) !!}
                        {!! Form::close() !!}
                    @endcan
                
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

    <script type="text/javascript">
    
        //Se define un objeto que contenga las caracteristicas particulares de cada calendario y luego se definen
        var calendarObj = {};
        calendarObj.customButtons = null;

        @if(isset($inspector))
            calendarObj.events = $('#url').val()+'/events/{{ $inspector->id }}';
        @else
            calendarObj.events = $('#url').val()+'/events';
        @endif

        calendarObj.eventClick = function(event)
        {
            //Resetar y setear el action el formulario de completar si existe el elemento
            if($('#completeAppointment')[0])
            {
                console.log('Entrooo');
                $('#completeAppointment')[0].reset();
                $('#completeAppointment').attr('action', $('#url').val()+'/'+event.id+'/complete');
            }

            //Resetar y setear el action el formulario de formato si existe el elemento
            if($('#fillFormat')[0])
            {
                $('#fillFormat')[0].reset();
                $('#fillFormat').attr('action', $('#url').val()+'/'+event.id+'/format');
            }

            //Cambiar el action del formulario
            $('#deleteAppointment').attr('action', $('#url').val()+'/'+event.id);
            $('.showCalendar').attr('data-route', $('#url').val()+'/'+event.id);
            
            if(event.appointment_states_id == 1){
                @can('edit_inspectionappointments')
                    $('.btns').html('<button class="btn btn-info btn-form-slide" data-toggle="#completeAppointment">@lang("words.Complete")</button>');
                @endcan
            }else if(event.appointment_states_id == 2){
                @can('edit_inspectionappointments')
                    $('.btns').html('<button data-toggle="#editAppointment" class="btn btn-primary editCalendar" data-route="'+$('#url').val()+'/'+event.id+'/edit'+'">@lang("words.Edit")</button>');
                @endcan

                if(event.format_id){
                    @can('edit_formats')
                        $('.btns').append('<button class="btn btn-default btn-form-slide" data-toggle="#fillFormat">@lang("words.Edit") @choice("words.Format", 1)</button>');
                    @endcan
                }else{
                    @can('add_formats')
                        $('.btns').append('<button class="btn btn-default btn-form-slide" data-toggle="#fillFormat">@lang("words.Create") @choice("words.Format", 1)</button>');
                    @endcan
                }
            }else{
                $('.btns').html('');
            }

            //Se limpia las alertas
            $('.msgError').html('');

            //Limpiar las validaciones
            $('.form-group').removeClass('has-error');
            $('.errors').empty();

            //Ocultar los formularios desplegables
            $(".formSlide").hide();

            $('#modalEditDel').modal('show');
        };

        @can('add_inspectionappointments')
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
        
        calendarObj.eventDrop = function(calEvent, delta, revertFunc)
        {
            @can('edit_inspectionappointments')
                var end = calEvent.end.format().split('T');

                $('#editAppointment').attr('action', $('#url').val()+'/'+calEvent.id);
                $('#modalEditDel #start_date').val(calEvent.start.format());
                $('#modalEditDel #end_date').val(end[0]);
                $('#modalEditDel #inspector_id').val(calEvent.inspector_id);

                confirmModal('#editAppointment', '{{trans('words.UpdateMessage')}}', 'question', revertFunc);
            @else

                swal('Error','No puede realizar esta acción, no tienes permisos','error');
                revertFunc();
            
            @endcan
        };

        //Se llama la función que inicializará el calendario de acuerdo al objeto enviado
        calendar(calendarObj);

    </script>   
@endsection