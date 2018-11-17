@extends('layouts.app')

@section('title', trans_choice('words.Inspectionappointment', 1))

@section('content')

    <div class="msgAlert"></div>    
    
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
                        <div class="col-md-7 page-action text-right">
                            @if(isset($id))
                                <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
                            @endif
                        </div>
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <ul style="list-style-type:none">
                @foreach($appointment_states as $state)
                    <li><span class="glyphicon glyphicon-bookmark tag-{{ $state->color }}"></span> {{$state->name}}</li>
                @endforeach
            </ul>  
        </div>
    </div>

        
        {{-- <ul style="list-style-type:none">
            <li><span class="glyphicon glyphicon-bookmark" style="color:#26b99ae0"></span> Activo</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#e74c3ce0"></span> Inactivo</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#f39c12e0"></span> Pendiente</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#3498dbe0"></span> En proceso</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#544948e0"></span> Finalizado</li>
        </ul>   --}}
    {{-- </div> --}}

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

                    {!! Form::open(['route' => ['inspectionappointments.store'], 'class' => 'formCalendar', 'id' => 'formCreateAgenda', 'data-modal'=>'#modalCreate']) !!}
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
                                {!! Form::text('start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                        </div>

                        <div class="form-group @if ($errors->has('end_date')) has-error @endif">
                            {!! Form::label('end_date', trans('words.EndDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
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
                                {!! Form::text('start_date', isset($agenda) ? $agenda['start_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                        </div>

                        <div class="form-group @if ($errors->has('end_date')) has-error @endif">
                            {!! Form::label('end_date', trans('words.EndDate')) !!}
                            <div class="input-group date">
                                {!! Form::text('end_date', isset($agenda) ? $agenda['end_date'] : null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
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

    <script type="text/javascript">

        $(document).ready(function(){

            //Campo hora
            $('.clockpicker').clockpicker();

            //Campo fecha
            $('.input-group.date').datepicker({
                forceParse: false,
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                orientation: "bottom auto",
                @if(app()->getLocale()=='es')
                language: "es",
                @endif
            });
            
            /*$(document).on('submit','.formCalendar',function(e){
                
                //console.log(confirm('Seguro que quieres borrarlo?'));
                
                var idForm = $(this).attr('id');
                var modal = $(this).data('modal');
                console.log(idForm);
                console.log(modal);
                console.log($('#'+idForm).serialize());

                e.preventDefault();                
                
                $.ajax({
                    url:$(this).attr('action'),
                    type:'POST',
                    data:$('#'+idForm).serialize(),
                    
                })
                .done(function(res){
                    console.log('done\n'+res);
                    var res = JSON.parse(res);   

                    if(res.error == null){
                        console.log("LLego");
                        $(modal).modal('hide');
                        $('#'+idForm)[0].reset();
                        $('.msgError').html('');
                        $("#calendar").fullCalendar('refetchEvents');

                        if(res.status != null){
                            $('.msgAlert').html('');
                            $('.msgAlert').append(alert('success', res.status));
                            $('.msgAlert').fadeIn('slow');

                            setTimeout(function(){
                                $('.msgAlert').fadeOut('slow');
                            },4000);
                        }
                    }else{
                        console.log("Errorrrrr");
                        $('.msgError').html('');
                        $('.msgError').append(alert('danger', res.error));
                    }
                })
                .fail(function(res){
                    console.log('error\n'+res);
                    console.log(res);
                })
                .always(function(res){
                    console.log('complete\n'+res);
                })
                .error(function(res){
                    $('.msgError').html('');
                    $.each( res.responseJSON.errors, function( key, value ) {
                        $('.msgError').append(alert('danger', value));
                    });
                });
            });*/

            $('.inspection_type_id').on('change',function(event, cita){
                /*console.log($(this).val());
                console.log(this.dataset.route);
                console.log($('#_token').val());*/
                $.ajax({
                    url:this.dataset.route,
                    type:'POST',
                    data:{
                        id: $(this).val(),
                        _token: $('#_token').val(),
                    }
                })
                .done(function(res){
                    
                    console.log('done\n'+res);
                    console.log(JSON.parse(res).status);
                    $('.inspection_subtype_id').html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
                    $.each(JSON.parse(res), function( key, value ) {
                        //$('.msgError').append(alert('danger', value));
                        console.log('Id: '+value.id+'\nName: '+value.name);
                        $('.inspection_subtype_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });

                    if(cita != undefined){
                        $('#modalEditDel #inspection_subtype_id').val(cita.inspection_subtype_id);
                    }
                })
                .fail(function(res){
                    alert('oiga mire vea, no hay internet.');
                })
                .always(function(res){
                    console.log('complete\n'+res);
                });
            });
        });

        //Calendario
        $("#calendar").fullCalendar({
            selectable: true,//Permite seleccionar
            nowIndicator: true,//Indicador del tiempo actual
            eventLimit: true, //Para que aparezca "ver más" en caso de muchas citas
            displayEventTime: false,
            @can('add_inspectionappointments')
                //Boton de crear
                customButtons: {
                    createButton: {
                        text: '{{trans('words.Create')}}',
                        click: function() {
                            $('.msgError').html('');
                            $('#modalCreate #date').removeAttr("disabled");
                            $('#formCreateAgenda')[0].reset();
                            $('#modalCreate').modal('show');
                        }
                    }
                },
            @endcan
            header:{
                "left":"prev,next today,createButton",
                "center":"title",
                "right":"month,agendaWeek,agendaDay,listMonth"
            },
            
            events: $('#url').val()+'/events',

            eventClick: function(calEvent, jsEvent, view) {
                /*//Separar en fecha[0] y hora[1]
                var start = calEvent.start.format().split('T');
                var end =  calEvent.end.format().split('T');
                console.log(calEvent.contract_id);
                console.log(calEvent.client_id);*/
                                
                //Se rellena el formulario de editar con los valores correspondientes
                /* $('#modalEditDel #inspector_id').val(calEvent.inspector_id);
                $('#modalEditDel #start_date').val(start[0]);
                $('#modalEditDel #end_date').val(end[0]); */

                /*$('#modalEditDel #inspection_type_id').val(calEvent.inspection_type_id);
                $('#modalEditDel #inspection_type_id').trigger('change',calEvent);
                $('#modalEditDel #appointment_location_id').val(calEvent.appointment_location_id);
                $('#modalEditDel #appointment_states_id').val(calEvent.appointment_states_id);
                $('#modalEditDel #estimated_start_date').val(start[0]);
                $('#modalEditDel #estimated_end_date').val(end[0]);
                $('#modalEditDel #contract_id').val(calEvent.contract_id);
                $('#modalEditDel #client_id').val(calEvent.client_id);*/

                //Resetar y setear el action el formulario de completar
                $('#completeAppointment')[0].reset();
                $('#completeAppointment').attr('action', $('#url').val()+'/'+calEvent.id+'/complete');

                //Cambiar el action del formulario
                $('#deleteAppointment').attr('action', $('#url').val()+'/'+calEvent.id);
                $('.showCalendar').attr('data-route', $('#url').val()+'/'+calEvent.id);
                /* $('#editAppointment').attr('action',  $('#url').val()+'/'+calEvent.id);
                $('#completeAppointment').attr('action', $('#url').val()+'/'+calEvent.id+'/complete');
 */
                
                if(calEvent.appointment_states_id == 1){
                    $('.btns').html('<button class="btn btn-info btn-form-slide" data-toggle="#completeAppointment">@lang("words.Complete")</button>');
                }else if(calEvent.appointment_states_id == 2){
                    $('.btns').html('<button data-toggle="#editAppointment" class="btn btn-primary editCalendar" data-route="'+$('#url').val()+'/'+calEvent.id+'/edit'+'">@lang("words.Edit")</button>');
                }else{
                    $('.btns').html('');
                }
                console.log('Estado: '+calEvent.appointment_states_id);

                //Se limpia las alertas
                $('.msgError').html('');

                //Ocultar los formularios desplegables
                $(".formSlide").hide();

                $('#modalEditDel').modal('show');
            },
            select: function(startDate, endDate, jsEvent, view) {
                //Separar en fecha[0] y hora[1]
                var start = startDate.format().split('T');

                var ed = new Date(endDate.format());
                ed = ed.getFullYear()+'-'+ ("0" + (ed.getMonth() + 1)).slice(-2) +'-'+("0" + ed.getDate()).slice(-2);
         
                //Validar se se secciono un rango de dias, de lo contrario pase al evento dayClick
                if(start != ed){
                    $('#formCreateAgenda')[0].reset();
                    $('.msgError').html('');
                    $('#estimated_start_date').val(start[0]);
                    $('#estimated_end_date').val(ed);
                    $('#modalCreate').modal('show');
                }
            },
            dayClick: function(date, jsEvent, view) {
                $('.msgError').html('');
                $('#formCreateAgenda')[0].reset();
                $('#modalCreate #estimated_start_date').val(date.format());
                $('#modalCreate #estimated_end_date').val(date.format());
                $('#modalCreate').modal('show');
            },
            editable: true,
            eventDrop: function(calEvent, delta, revertFunc){
                var end = calEvent.end.format().split('T');

                $('#editAppointment').attr('action', $('#url').val()+'/'+calEvent.id);
                $('#modalEditDel #start_date').val(calEvent.start.format());
                $('#modalEditDel #end_date').val(end[0]);
                $('#modalEditDel #inspector_id').val(calEvent.inspector_id);

                confirmModal('#editAppointment', '{{trans('words.UpdateMessage')}}', 'question', revertFunc);
            },
        });

    </script>   
@endsection