@extends('layouts.app')

@section('title', trans('words.InspectorAgenda'))

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-clockpicker.css')}}">
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


    {{-- <div class="container"> --}}
        {{-- <div class="row"> --}}
        
            <div class="col-md-8 col-md-offset-2">
                {{-- <div class="row">
                    <div class="col-md-5">
                        <h3 class="modal-title">{{ $result->total() }} {{ str_plural(trans('words.InspectorAgenda'), $result->count()) }} </h3>
                    </div>
                    <div class="col-md-7 page-action text-right">
                        @can('add_clients')
                            <a href="{{ route('inspectoragendas.create') }}" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-plus-sign"></i> @lang('words.Create')</a>
                        @endcan
                    </div>
                </div> --}}

                <div class="panel panel-default">
                    <div class="panel-heading">{{-- @lang('words.InspectorAgenda') --}}
                        <h3 class="">@lang('words.AvailableAppointments')</h3>
                        <a href="{{ route('inspectoragendas.view', 'list') }}">Lista tabla</a>
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
                    {!! Form::open(['route' => ['inspectoragendas.store']]) !!}
                        {{-- <!-- Date of Inspector Agenda Form Date -->
                        <div class="form-group @if ($errors->has('date')) has-error @endif">
                            {!! Form::label('date', trans('words.Date')) !!}
                            {!! Form::text('date', null, ['class' => 'input-body', 'readonly']) !!}
                            @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
                        </div>

                        <!-- Start Time of Inspector Agenda Form Time -->
                            {!! Form::label('start_time', trans('words.StartTime')) !!}
                        <div class="input-group clockpicker @if ($errors->has('start_time')) has-error @endif" data-autoclose="true">
                            {!! Form::text('start_time', null, ['class' => 'form-control']) !!}
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                            @if ($errors->has('start_time')) <p class="help-block">{{ $errors->first('start_time') }}</p> @endif
                        </div>

                        <!-- End Time of Inspector Agenda Form Time -->
                            {!! Form::label('end_time', trans('words.EndTime')) !!}
                        <div class="input-group clockpicker @if ($errors->has('end_time')) has-error @endif" data-autoclose="true">
                            {!! Form::text('end_time', null, ['class' => 'form-control']) !!}
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                            </span>
                            @if ($errors->has('end_time')) <p class="help-block">{{ $errors->first('end_time') }}</p> @endif
                        </div>

                        <!-- Inspector of Headquarters Form Select -->
                        <div class="form-group @if ($errors->has('inspector_id')) has-error @endif">
                            {!! Form::label('inspector_id', trans('words.Inspectors')) !!}
                            <select name="inspector_id" id="inspector_id" class="input-body">
                                <option value="">@lang('words.ChooseOption')</option>
                                @foreach($inspectors as $item)
                                    <option value="{{$item->id}}" 
                                    @if(isset($inspectorAgenda))
                                    {{ $inspectorAgenda->inspector_id === $item->id ? 'selected' : '' }}
                                    @endif
                                    >{{$item->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('inspector_id')) <p class="help-block">{{ $errors->first('inspector_id') }}</p> @endif
                        </div>

                        <!-- Headquarters of Headquarters Form Select -->
                        <div class="form-group @if ($errors->has('headquarters_id')) has-error @endif">
                            {!! Form::label('headquarters_id', str_plural(trans('words.Headquarters'),2)) !!}
                            <select name="headquarters_id" id="headquarters_id" class="input-body">
                                <option value="">@lang('words.ChooseOption')</option>
                                @foreach($headquarters as $item)
                                    <option value="{{$item->id}}" 
                                    @if(isset($inspectorAgenda))
                                    {{ $inspectorAgenda->headquarters_id === $item->id ? 'selected' : '' }}
                                    @endif
                                    >{{$item->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('headquarters_id')) <p class="help-block">{{ $errors->first('headquarters_id') }}</p> @endif
                        </div> --}}
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
                <div class="modal-body text-center">
                    <a class="btn btn-primary" id="editInspectorAgenda">@lang('words.Edit')</a>
                    <form method="POST" onsubmit="return confirm('Seguro que quieres borrarlo?')" id="deleteInspectorAgenda" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="">@lang('words.Delete')</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('words.Close')</button>
                </div>
            </div>
      
        </div>
    </div>

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
    {{-- <script src="{{asset('vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/dist/fullcalendar.min.js')}}"></script> --}}

    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/fullcalendar.min.js')}}"></script>
    {{-- <script src="{{asset('js/es.js')}}"></script> --}}
    @if(app()->getLocale()=='es')
        <script src="{{asset('js/es.js')}}"></script>
    @endif

    <script type="text/javascript" >

        $(document).ready(function(){

            //Campo hora
            $('.clockpicker').clockpicker();

            //Campo fecha
            $('.input-group.date').datepicker({
                //forceParse: false,
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                orientation: "bottom auto",
                @if(app()->getLocale()=='es')
                language: "es",
                @endif
            });
        });

        //Calendario
        $("#calendar").fullCalendar({
            selectable: true,//Permite seleccionar
            nowIndicator: true,//Indicador del tiempo actual
            eventLimit: true, //Para que aparezca "ver más" en caso de muchas citas
            //eventColor: 'green',
            //eventTextColor: 'green',
            eventRender: function(eventObj, $el) {
                $el.popover({//Ventana desplegable al hacer hover a un evento
                    title: eventObj.inspector,
                    content: eventObj.headquarter,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },
            customButtons: {//Boton de crear
                createButton: {
                    text: '{{trans('words.Create')}}',
                    click: function() {
                        location.href ="{{ route('inspectoragendas.create') }}";
                    }
                }
            },
            header:{
                "left":"prev,next today,createButton",
                "center":"title",
                "right":"month,agendaWeek,agendaDay,listMonth"
            },
            
            events: [
                @foreach($result as $item)
                    {
                    //title  : '{{$item->id.' '.substr($item->start_time, 0, -3).'-'.substr($item->end_time, 0, -3)}}',
                    inspector : '{{$item->inspector->name}}',
                    headquarter : '{{$item->headquarters->name}}',
                    slug : '{{$item->slug}}',
                    allDay : false,
                    start : '{{$item->date.'T'.$item->start_time}}',
                    end  : '{{$item->date.'T'.$item->end_time}}',
                    //color: 'purple',
                    },
                    //console.log("{{$item->start_time}}");
                @endforeach
            ],
            eventClick: function(calEvent, jsEvent, view) {
                //Se cambia el enlace del boton editar para redireccionarlo a la vista editar de la cita seleccionada
                $('#editInspectorAgenda').attr('href', window.location.href+'/'+calEvent.slug+'/edit ');
                $('#deleteInspectorAgenda').attr('action', window.location.href+'/'+calEvent.slug);
                $('#modalEditDel').modal('show');

                // change the border color just for fun
                //$(this).css('border-color', 'red');

            },
            select: function(startDate, endDate, jsEvent, view) {
                var start = startDate.format().split('T');//Separar en fecha[0] y hora[1]
                var end =  endDate.format().split('T');
                //console.log(end[0]);
                //console.log(start[0]);
                if(end[1] != undefined && end[0] == start[0]){//Validar si se selecciono un día u horas en un rango de dos días
                    $('#date').val(start[0]);
                    $('#start_time').val(start[1]);
                    $('#end_time').val(end[1]);
                    $('#date').attr('disabled', 'disabled');
                    $('.input-group.date').append('<input type="hidden" name="date" value="'+start[0]+'">');
                    $('#modalCreate').modal('show');
                }
            },
            /* viewRender(view, element){
                //$('.fc-title').css('text-align':'center', 'font-size':'2em');
                console.log(view.name);
            } */
        });

    </script>

        
@endsection