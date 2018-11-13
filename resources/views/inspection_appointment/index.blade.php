@extends('layouts.app')

@section('title', trans_choice('words.Inspectionappointment', 1))

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-clockpicker.css')}}">

    <!-- FullCalendar -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/fullcalendar.min.css')}}">
@endsection

@section('content')

    <div class="msgAlert"></div>    
    
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">{{-- @lang('words.InspectorAgenda') --}}
                <div class="row">
                    <div class="col-md-5">
                        @if(isset($id))
                            <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Inspectionappointment', $result->count()) }}  @lang('words.Of') {{ $result[0]->inspector['name'] }}  </h3>
                        @else
                            <h3 class="modal-title">{{ $result->total() }} {{ trans_choice('words.Inspectionappointment', $result->count()) }} </h3>
                        @endif
                        {{-- <h3 class="">@lang('words.AvailableAppointments')</h3> --}}
                    </div>
                    <div class="col-md-7 page-action text-right">
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
            <li><span class="glyphicon glyphicon-bookmark" style="color:#26b99ae0"></span> Activo</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#e74c3ce0"></span> Inactivo</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#f39c12e0"></span> Pendiente</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#3498dbe0"></span> En proceso</li>
            <li><span class="glyphicon glyphicon-bookmark" style="color:#544948e0"></span> Finalizado</li>
        </ul>  
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

                    {!! Form::open(['route' => ['inspectionappointments.store'], 'class' => 'formCalendar', 'id' => 'formCreateAgenda', 'data-modal'=>'modalCreate']) !!}
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

                        {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar', 'id' => 'editAppointment', 'data-modal'=>'modalEditDel']) !!}
                            @include('inspection_appointment._form')
                            <!-- Submit Form Button -->                        
                            {!! Form::submit(trans('words.Edit'), ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                        
                        <form method="POST" onsubmit="return confirm('Seguro que quieres borrarlo?')" id="deleteAppointment" class="formCalendar" data-modal="modalEditDel" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" id="">@lang('words.Delete')</button>
                        </form>
                    </form>
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

    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker.es.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-clockpicker.js')}}"></script>
    
    @if($errors->any())
        <script>
            $('#date').attr('disabled', 'disabled');
            console.log($('#date').val());
            $('.input-group.date').append('<input type="hidden" name="date" value="'+$("#date").val()+'">');
            $('#modalCreate').modal('show');
        </script>
    @endif

    <!-- FullCalendar -->
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/fullcalendar.min.js')}}"></script>

    {{-- Cambiar el idioma del calendario --}}
    @if(app()->getLocale()=='es')
        <script src="{{asset('js/es.js')}}"></script>
    @endif

    <script type="text/javascript" >

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
            
            $(document).on('submit','.formCalendar',function(e){
                var idForm = $(this).attr('id');
                var modal = this.dataset.modal;
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
                        $('#'+modal).modal('hide');
                        $('#'+idForm)[0].reset();
                        $('.msgError').html('');
                        $("#calendar").fullCalendar('refetchEvents');

                        if(res.status != null){
                            $('.msgAlert').html('');
                            $('.msgAlert').append(alert('success', res.status));
                        }
                    }else{
                        console.log("Errorrrrr");
                        $('.msgError').html('');
                        $('.msgError').append(alert('danger', res.error));                       
                    }
                })
                .fail(function(res){
                    console.log('error\n'+res);
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
            });

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

        function alert(color, msg){
            return '<div class="alert alert-'+color+' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>';
        }

        //Calendario
        $("#calendar").fullCalendar({
            selectable: true,//Permite seleccionar
            nowIndicator: true,//Indicador del tiempo actual
            eventLimit: true, //Para que aparezca "ver más" en caso de muchas citas
            eventRender: function(eventObj, $el) {
                $el.popover({//Ventana desplegable al hacer hover a un evento
                    title: eventObj.inspector,
                    content: eventObj.type+' - '+eventObj.subType,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },
            @can('add_inspectionappointments')
                customButtons: {//Boton de crear
                    createButton: {
                        text: '{{trans('words.Create')}}',
                        click: function() {
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
                //Separar en fecha[0] y hora[1]
                var start = calEvent.start.format().split('T');
                var end =  calEvent.end.format().split('T');
                console.log(calEvent.inspector_id);
                console.log(calEvent.inspection_type_id);
                console.log(calEvent.appointment_location_id);
                console.log(calEvent.appointment_states_id);
                console.log(start[0]);
                console.log(start[1]);
                console.log(end[0]);
                console.log(end[1]);
                
                //Se rellena el formulario de editar con los valores correspondientes
                //$('#modalEditDel #date').val(start[0]);
                $('#modalEditDel #inspector_id').val(calEvent.inspector_id);
                $('#modalEditDel #inspection_type_id').val(calEvent.inspection_type_id);
                $('#modalEditDel #inspection_type_id').trigger('change',calEvent);
                $('#modalEditDel #appointment_location_id').val(calEvent.appointment_location_id);
                $('#modalEditDel #appointment_states_id').val(calEvent.appointment_states_id);
                $('#modalEditDel #date').val(start[0]);
                $('#modalEditDel #start_time').val(start[1]);
                $('#modalEditDel #end_time').val(end[1]);

                console.log('Subtipo: '+calEvent.inspection_subtype_id);
                //Cambiar el action del formulario
                $('#editAppointment').attr('action',  $('#url').val()+'/'+calEvent.id);
                $('#deleteAppointment').attr('action', $('#url').val()+'/'+calEvent.id);

                //Se limpia las alertas
                $('.msgError').html('');
                $('#modalEditDel').modal('show');
            },
            select: function(startDate, endDate, jsEvent, view) {
                //Separar en fecha[0] y hora[1]
                var start = startDate.format().split('T');
                var end =  endDate.format().split('T');

                //console.log(end[0]);
                //console.log(start[0]);
                
                //Validar si se selecciono un día u horas en un rango de dos días
                if(end[1] != undefined && end[0] == start[0]){
                    $('#formCreateAgenda')[0].reset();
                    $('.msgError').html('');
                    $('#date').val(start[0]);
                    $('#start_time').val(start[1]);
                    $('#end_time').val(end[1]);
                    $('#date').attr('disabled', 'disabled');
                    $('.input-group.date').append('<input type="hidden" name="date" value="'+start[0]+'">');
                    $('#modalCreate').modal('show');
                }
            },
            dayClick: function(date, jsEvent, view) {
                $('#formCreateAgenda')[0].reset();
                $('#modalCreate #date').val(date.format());
                $('#modalCreate #date').removeAttr("disabled");
                $('#modalCreate').modal('show');
            }
        });

    </script>   
@endsection