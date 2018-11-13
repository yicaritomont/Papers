@extends('layouts.app')

@section('title', trans_choice('words.InspectorAgenda', 1))

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-clockpicker.css')}}">
    <meta>
    <!-- FullCalendar -->
        {{-- <link href="{{asset('vendors/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
        <link href="{{asset('vendors/fullcalendar/dist/fullcalendar.print.css')}}" rel="stylesheet" media="print"> --}}

        {{-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/> --}}

        <link rel="stylesheet" type="text/css" href="{{asset('css/fullcalendar.min.css')}}">
        {{-- <script src="{{asset('js/moment.min.js')}}"></script>
        <script src="{{asset('js/fullcalendar.js')}}"></script> --}}
        
        
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css"> --}}

@endsection

@section('content')
    {{date('Y-m-d')}}    

    <div class="msgAlert"></div>
    {{-- <div class="container"> --}}
        {{-- <div class="row"> --}}
        
            <div class="col-md-8 col-md-offset-2">
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
                                <a class="btn btn-info" href="{{ route('inspectoragendas.view') }}">@lang('words.tableView')</a>
                                @if(isset($id))
                                    <a href="{{ route('inspectors.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        {{-- </div> --}}
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
                    {!! Form::open(['route' => ['inspectoragendas.store.ajax'], 'class' => 'formCalendar', 'id' => 'formCreateAgenda', 'data-modal'=>'modalCreate']) !!}
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

                    {!! Form::open(['method' => 'PUT', 'class' => 'formCalendar', 'id' => 'editAgenda', 'data-modal'=>'modalEditDel']) !!}
                        @include('inspector_agenda._form')
                        <!-- Submit Form Button -->                        
                        {!! Form::submit(trans('words.Edit'), ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                    {{-- <a class="btn btn-primary" id="editInspectorAgenda">@lang('words.Edit')</a> --}}
                    <form method="POST" onsubmit="return confirm('Seguro que quieres borrarlo?')" id="deleteAgenda" class="formCalendar" data-modal="modalEditDel" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="">@lang('words.Delete')</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button style="display:inline" type="button" class="btn btn-default" data-dismiss="modal">@lang('words.Close')</button>
                </div>
            </div>
      
        </div>
    </div>

    <input type="hidden" id="url" value="{{ route('inspectoragendas.events') }}">
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

            $('.country').on('change',function(event, agenda){
                console.log($(this).val());
                console.log(this.dataset.route);
                console.log($('#_token').val());
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
                    $('.city_id').html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
                    $.each(JSON.parse(res), function( key, value ) {
                        //$('.msgError').append(alert('danger', value));
                        console.log('Id: '+value.id+'\nName: '+value.name);
                        $('.city_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });

                    if(agenda != undefined){
                        $('#modalEditDel #city_id').val(agenda.city_id);
                    }
                })
                .fail(function(res){
                    alert('Error\n'+res);
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
            displayEventTime: true,

            eventRender: function(eventObj, $el) {
                $el.popover({//Ventana desplegable al hacer hover a un evento
                    title: eventObj.inspector,
                    content: eventObj.city,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },
            
            @can('add_inspectoragendas')
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
                "right":"month,agendaWeek,listMonth"
            },
            events: $('#url').val(),

            eventClick: function(calEvent, jsEvent, view) {
                //Separar en fecha[0] y hora[1]
                var start = calEvent.start.format();
                if(calEvent.end == null){
                    console.log('Un solo día');
                    var end =  start;
                }else{
                    var end =  calEvent.end.format();
                }
                console.log(start);
                console.log(end);

                //Se rellena el formulario de editar con los valores correspondientes
                //$('#modalEditDel #date').val(start[0]);
                $('#modalEditDel #start_date').val(start);
                $('#modalEditDel #end_date').val(end);
                $('#modalEditDel #inspector_id').val(calEvent.inspector_id);
                $('#modalEditDel #country').val(calEvent.country_id);
                $('#modalEditDel #country').trigger('change',calEvent);

                //Cambiar el action del formulario
                $('#editAgenda').attr('action',  $('#url').val()+'/ajax/'+calEvent.slug);
                $('#deleteAgenda').attr('action', $('#url').val()+'/ajax/'+calEvent.slug);

                //Se limpia las alertas
                $('.msgError').html('');
                $('#modalEditDel').modal('show');
            },
            select: function(startDate, endDate, jsEvent, view) {
                //Separar en fecha[0] y hora[1]
                var start = startDate.format().split('T');
                var end =  endDate.format();

                var ed = new Date(end);
                ed = ed.getFullYear()+'-'+ (ed.getMonth()+1) +'-'+ed.getDate();
                /*console.log('Fecha inicio: '+start);
                console.log('Fecha final: '+ed);*/
         
                //Validar si se selecciono un día u horas en un rango de dos días
                //if(end[1] != undefined && end[0] == start[0]){
                //Validar se se secciono un rango de dias, de lo contrario pase al evento dayClick
                if(start != ed){
                    console.log('Selecciono un conjunto de dias');
                    $('#formCreateAgenda')[0].reset();
                    $('.msgError').html('');
                    $('#start_date').val(start[0]);
                    $('#end_date').val(ed);
                    //$('#date').attr('disabled', 'disabled');
                    //$('.input-group.date').append('<input type="hidden" name="date" value="'+start[0]+'">');
                    $('#modalCreate').modal('show');
                }
            },
            dayClick: function(date, jsEvent, view) {
                console.log('Clickeo');
                console.log(date.format());
                $('.msgError').html('');
                $('#formCreateAgenda')[0].reset();
                $('#modalCreate #start_date').val(date.format());
                $('#modalCreate #end_date').val(date.format());
                //console.log(date.format());
                //$('#modalCreate #date').removeAttr("disabled");
                $('#modalCreate').modal('show');
            },
            //editable: true,
            /*eventDrop: function(calEvent){
                var start = calEvent.start.format().split('T');
                var end = calEvent.end.format().split('T');

                console.log('Date_Start: '+start[0]);
                console.log('Time_Start: '+start[1]);
                console.log('Date_End: '+end[0]);
                console.log('Time_End: '+end[1]);
            },*/
        });

    </script>    
@endsection