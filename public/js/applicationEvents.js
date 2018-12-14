var guiaAgendas = [];

$(window).ready(inicial);

$(window).resize(function(){
    changeTopToast();
    // $('.right_col>.row').css('margin-top', $('.nav_menu').height()+'px');
});

function inicial (argument)
{
     //Eventos de los botones para solicitud de turno cliente interno
    $('#password_update').keyup(verifyPassword);
    $('#password-confirm').blur(verifyPassword);
    $('#identificacion_inspector').blur(verifyInspector);
    $('#boton_guardar_html').click(guardarHtml);
    $('#boton_firmar_formato').click(deshabilitarCampos);
    $('#boton_firmar_formato').click(guardarHtml);
    // $('#company_formato').change(cargarSelectClients);
    $('#company_formato').on('change', function(event, edit){
        fillSelect(window.Laravel.url+'/companies/clients/'+$(this).val(), '#cliente_formato', edit, () => {
            $('#format_preformato').val('');
            $('#cliente_formato').change(limpiarFormulario);
            // $('#format_preformato').change(llenarCabeceraFormato);
            $('#plantilla_formato').css('display','none');
            $('#contenedor_formato').css('display','none');
        });
    });
    $('#format_preformato').change(llenarCabeceraFormato);

    //Se definen atributos generales de DataTable
    dataTableObject = {
        responsive: true,
        serverSide: true,
        processing: true,
    };


    //Campo fecha

    var datePickerObj = {
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        orientation: "bottom auto",
        forceParse: false,
    };

    //Se valida el idioma
    if(window.Laravel.language == 'es'){
        dataTableObject.language = {url: window.Laravel.url+'/js/lib/dataTable/Spanish.json'};
        datePickerObj.language = 'es';
        chosenText = 'No hay coincidencias para ';
    }else{
        chosenText = 'No matches for';
    }

    /* $(".chosen-select").chosen({
        no_results_text: chosenText,
    }); */

    $('.select2').select2();

    $('.input-group.date').datepicker(datePickerObj);

    //Para que no permita seleccionar un dia antertior al actual
    datePickerObj.startDate = new Date();

    $('.input-group.date-range-inputs input').datepicker(datePickerObj);
}

// Si existe el campo icon cargue el archivo con todos los iconos de font awesome
if($('#icon')[0])
{
    $.getScript(window.Laravel.url+'/js/icons.js', function( data, textStatus, jqxhr )
    {
        var iconos='<div id="text"></div><ul>';
        $.each(fA, function(key, value){
            iconos += '<li title="'+value+'"><i data-icon="'+value+'" class="fa '+value+'"></i></li>';
        });

        iconos+="</ul>";

        $('#icon').parent().after("<div class='oculto'>"+iconos+"</div>");
    });
}

// Pintar las citas en el calendario de acuerdo a la compañia seleccionada
$('#citas-compania').on('change', function(event){
    var companyVal = $(this).val();
    
    $('.fc-day.bgEvent').removeClass('bgEvent');
    $('#appointment_loading').css('display', 'inline-block');
    $('#company_id').val(companyVal);

    if(companyVal){
        $('#subtypeFilter').attr('disabled', false);
    }else{
        $('#subtypeFilter').attr('disabled', true);
    }

    // Se eliminan los origines de los eventos
    $("#calendar").fullCalendar('removeEventSources');

    // Se añade un origen de evento de acuerdo a la compañía seleccionada
    $("#calendar").fullCalendar('addEventSource', {
        url:$('#url').val()+'/events/company/'+companyVal,
        type:'POST',
        data:{ _token: window.Laravel.csrfToken},
        success: function(){
            $('#appointment_loading').hide();

            //Cargue los subtipos
            $('#subtypeFilter').trigger('change');

            //Llene el select clientes
            fillSelect(window.Laravel.url+'/companies/clients/'+companyVal, '#client_id');
        }
    });
});

$('#subtypeFilter').on('change', function(event, edit){
    $('#inspection_subtype_id').val($(this).val());

    if($(this).val()){
       // ajax parameters: url, Method, data, Function done, Function error(optional)
        ajax(
            window.Laravel.url+'/inspectoragendas/subtype',
            'POST',
            {_token: $('#_token').val(),
            subtype_id: $(this).val(),
            company_id: $('#citas-compania').val()},
            (res) => {
                if(res.msg){
                    $('.fc-day.bgEvent').removeClass('bgEvent');
                    swal({
                        type: 'warning',
                        titleText: res.msg
                    });
                }else{
                    guiaAgendas = [];
                    $.each(res.agendas, function(key, value){
                        guiaAgendas.push(value);
                    });
                    
                    colorearAgendas();
                }
            }
        ); 
    }else{
        $('.fc-day.bgEvent').removeClass('bgEvent');
    }
});

function colorearAgendas()
{
    $('.fc-day.bgEvent').removeClass('bgEvent');
    $.each(guiaAgendas, function(key, objAgenda){
        $('.fc-day[data-date="'+objAgenda+'"]').addClass('bgEvent');
    });
}

//Todos los select que requieran una petición ajax para llenar otro select
$('#company_id').on('change', function(event, edit){
    fillSelect(window.Laravel.url+'/companies/clients/'+$(this).val(), '#client_id', edit);
});

$('.inspection_type_id').on('change',function(event, edit){
    fillSelect(window.Laravel.url+'/inspectiontypes/subtypes/'+$(this).val(), '.inspection_subtype_id', edit);
});

/* $('.inspector-contract').on('change',function(event, edit){
    fillSelect(window.Laravel.url+'/inspectors/contracts/'+$(this).val(), '#contract_id', edit, () => {
        $('#contract_id').trigger('change');
    });
}); */

$('.country').on('change',function(event, edit){
    fillSelect(window.Laravel.url+'/country/cities/'+$(this).val(), '.city_id', edit);
});

$('.client-contract').on('change', function(event, edit){
    console.log('Cliente cambio');
    fillSelect(window.Laravel.url+'/clients/contracts/'+$(this).val(), '#contract_id', edit);
});

/* // Actualización campo cliente en base al formato seleccionado
$('#contract_id').on('change',function(event){
    $('#client_id_loading').css('display', 'inline-block');

    // ajax parameters: url, Method, data, Function done, Function error(optional)
    ajax(
        window.Laravel.url+'/contracts/clients/'+$(this).val(),
        'GET',
        {_token: $('#_token').val()},
        (res) => {
            $('#client_id').val(res.client);
            $('#client_id_loading').hide();
        }
    );
}); */

// Evento para ocultar o mostrar elementos sin datos consultados
$(document).on('click', '.btn-form-slide', function(){ slideForms($(this)) });

function formatDateTable(targets){
    return {
        targets:targets,
        render: function(data, type, row, meta){
            var date = moment.tz(data.replace(' ', 'T')+'Z', moment.tz.guess());

            return date.format('MMMM DD YYYY, h:mm:ss a');
        }
    };
}

function obtenerUrl()
{
    //Obtiene la url del documento actual desde el directorio padre
    var rutaAbsoluta =  window.location.pathname;
    //Convierte en un array que contiene los string separados por el slash "/"
    var vector = rutaAbsoluta.split('/');

    //Concatena la informacion para construir la url
    var url = window.location.protocol+'//'+window.location.host+'/'+vector[1];
    
    return url;
}

function renderizarNotificacionModal(idModal,idContenedor,mensaje)
{
    $('#'+idModal).modal('show');
    $('#'+idContenedor).empty();
    $('#'+idContenedor).html(mensaje);
    setTimeout("ocultarModal('"+idModal+"')", 5000);
}

function ocultarModal(idModal)
{
    $('#'+idModal).modal('hide');
    $('.btn-lg').removeAttr('disabled');
}

function verifyPassword()
{
    var newPassword = $(this).val();
    var userPassword = $('#user_password').val();
    var confirmPassword = $('#password-confirm').val();
    if(newPassword != "")
    {
        $('#changePassword').attr('disabled','disabled');
        $.ajax({
            type: "GET",
            url: obtenerUrl()+"/public/ajxVerifyPassword",
            dataType:'json',
            data: {newPassword:newPassword , userPassword : userPassword ,confirmPassword : confirmPassword}
            }).done(function( response)
                {
                    if(!jQuery.isEmptyObject(response.notificacion))
                    {

                        $('#changePassword').removeAttr('disabled');
                        $('#div_info_lengthPwd').html("");
                        $('#div_info_lengthNumber').html("");
                        $('#div_info_lengthLower').html("");
                        $('#div_info_lengthUpper').html("");
                        $('#div_info_beforePass').html("");
                        $('#div_info_keyWordPass').html("");
                        $('#div_info_confirmPass').html("");
                        $('#div_info_lengthPwd').removeClass("text-danger");
                        $('#div_info_lengthNumber').removeClass("text-danger");
                        $('#div_info_lengthLower').removeClass("text-danger");
                        $('#div_info_lengthUpper').removeClass("text-danger");
                        $('#div_info_beforePass').removeClass("text-danger");
                        $('#div_info_keyWordPass').removeClass("text-danger");
                        $('#div_info_confirmPass').removeClass("text-danger");
                        //renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal',response.notificacion);
                    }
                    else
                    {
                        //div_info_beforePass
                        if(response.message != "")
                        {
                            if(response.message.message.lengthPwd)
                            {
                                //alert(response.message.message.lengthPwd);
                                $('#div_info_lengthPwd').html(response.message.message.lengthPwd);
                                $('#div_info_lengthPwd').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_lengthPwd').html("");
                                $('#div_info_lengthPwd').removeClass("text-danger");
                            }

                            if(response.message.message.lengthNumber)
                            {
                                //alert(response.message.message.lengthNumber);
                                $('#div_info_lengthNumber').html(response.message.message.lengthNumber);
                                $('#div_info_lengthNumber').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_lengthNumber').html("");
                                $('#div_info_lengthNumber').removeClass("text-danger");
                            }

                            if(response.message.message.lengthLower)
                            {
                               // alert(response.message.message.lengthLower);
                                $('#div_info_lengthLower').html(response.message.message.lengthLower);
                                $('#div_info_lengthLower').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_lengthLower').html("");
                                $('#div_info_lengthLower').removeClass("text-danger");
                            }

                            if(response.message.message.lengthUpper)
                            {
                                //alert(response.message.message.lengthUpper);
                                $('#div_info_lengthUpper').html(response.message.message.lengthUpper);
                                $('#div_info_lengthUpper').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_lengthUpper').html("");
                                $('#div_info_lengthUpper').removeClass("text-danger");
                            }

                            if(response.message.message.beforePass)
                            {
                                //alert(response.message.message.lengthUpper);
                                $('#div_info_beforePass').html(response.message.message.beforePass);
                                $('#div_info_beforePass').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_beforePass').html("");
                                $('#div_info_beforePass').removeClass("text-danger");
                            }

                            if(response.message.message.keyWordPass)
                            {
                                $('#div_info_keyWordPass').html(response.message.message.keyWordPass);
                                $('#div_info_keyWordPass').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_keyWordPass').html("");
                                $('#div_info_keyWordPass').removeClass('text-danger');
                            }


                            if(response.message.message.confirmPass)
                            {
                                $('#div_info_confirmPass').html(response.message.message.confirmPass);
                                $('#div_info_confirmPass').addClass("text-danger");
                            }
                            else
                            {
                                $('#div_info_confirmPass').html("");
                                $('#div_info_confirmPass').removeClass('text-danger');
                            }
                        }
                        else
                        {
                            $('#div_info_lengthPwd').html("");
                            $('#div_info_lengthNumber').html("");
                            $('#div_info_lengthLower').html("");
                            $('#div_info_lengthUpper').html("");
                            $('#div_info_beforePass').html("");
                            $('#div_info_keyWordPass').html("");
                            $('#div_info_confirmPass').html("");
                            $('#changePassword').removeAttr('disabled');
                            $('#div_info_lengthPwd').removeClass("text-danger");
                            $('#div_info_lengthNumber').removeClass("text-danger");
                            $('#div_info_lengthLower').removeClass("text-danger");
                            $('#div_info_lengthUpper').removeClass("text-danger");
                            $('#div_info_beforePass').removeClass("text-danger");
                            $('#div_info_keyWordPass').removeClass("text-danger");
                            $('#div_info_confirmPass').removeClass("text-danger");
                            //renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal','OK');
                        }

                    }
                }
            );
    }
}

//Retorna los mensajes de alerta
function alert(color, msg){
    return '<div class="alert alert-'+color+' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>';
}

function spanError(error){
    return '<p class="help-block">'+error+'</p>';
}

// Ocultar o mostrar elementos con animación slide
function slideForms(obj, funcRes) {
    var selector = obj.data('toggle');
    $('.formSlide:not('+selector+')').slideUp('slow');
    $(selector).slideToggle('slow', funcRes);
};

//Funcion para el mensaje de confirmación de eliminación por Ajax
function confirmModal(form, msg, type, revertFunc){
    if(document.documentElement.lang == 'en'){
        var confirmButtonText = 'Yes';
    }else{
        var confirmButtonText = 'Si';
    }

    swal({
            title: msg,
            type: type,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
            if(revertFunc){
                $(form).trigger('submit', [false, revertFunc]);
            }else{
                $(form).trigger('submit', true);
            }
        }else{
            if (revertFunc) revertFunc();
        }
    });
}

function changeTopToast(){
    $('.swal2-top-end').css('top', $('.nav_menu').outerHeight());
}

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
});

// Ajax para los formularios de eliminar exceptuando los calendarios
$(document).on('submit','.formDelete',function(e)
{
    e.preventDefault();

    // ajax parameters: url, Method, data, Function done, Function error(optional)
    ajax(
        $(this).attr('action'),
        'POST',
        $(this).serialize(),
        (res) => {
            //Si no exite algun error
            if(!res.error){

                toast({
                    type: 'success',
                    title: res.status
                });

                changeTopToast();

                $('.dataTable').DataTable().ajax.reload();
            }else{
                toast({
                    type: 'error',
                    title: res.error
                });
                changeTopToast();
            }
        },
        (res) => {
            if(res.status == 403){
                toast({
                    type: 'error',
                    title: res.responseJSON.message
                });
                changeTopToast();
            }
        }
    );
});

// Ajax para los formularios crear, actualizar y eliminar de los calendarios
$(document).on('submit','.formCalendar',function(e, salida, revertFunc){
    var idForm = $(this).attr('id');
    var modal = this.dataset.modal;
    if(revertFunc){
        var datos = $('#'+idForm).serialize()+'&drop=drop';
    }else{
        var datos = $('#'+idForm).serialize();
    }

    e.preventDefault();

    // ajax parameters: url, Method, data, Function done, Function error(optional)
    ajax(
        $(this).attr('action'),
        'POST',
        datos,
        (res) => {
            //Si no exite algun error
            if(!res.error){

                $(modal).modal('hide');
                $('#'+idForm)[0].reset();
                $('.msgError').html('');
                $("#calendar").fullCalendar('refetchEvents');

                toast({
                    type: 'success',
                    title: res.status
                });

                //Actualizar los días disponibles
                $('#subtypeFilter').trigger('change');

                changeTopToast();
            }else{
                //Si la respuesta es en modal
                if(salida == true){
                    swal('Error',res.error,'error');

                //Si la respuesta es en toast
                }else if(revertFunc){
                    revertFunc();
                    toast({
                        type: 'error',
                        title: res.error
                    });
                    changeTopToast();
                }
                else{
                    $('.msgError').html('');
                    $('.msgError').append(alert('danger', res.error));
                }

            }
        },
        (res) => {
            if(res.status == 403){
                $('.msgError').html('');
                $('.msgError').append(alert('danger', res.responseJSON.message));
            }else if(res.status == 422){
    
                $('.form-group').removeClass('has-error');
                $('.errors').empty();
    
                $('#'+idForm).find(':input').each(function(){
                    var idInput = $(this).attr('name');
                    /* console.log(idInput);
                    console.log($(this).attr('name')); */
                    if(idInput !== undefined && res.responseJSON.errors[idInput] !== undefined){
                        $(this).parents('.form-group').addClass('has-error');
                        $(this).parents('.form-group').find('.errors').append(spanError(res.responseJSON.errors[idInput][0]));
                    }
                });
            }
        }
    );
});

// Ajax para ver agendas y citas
$('.showCalendar').on('click', function(e){
    var objElement = $(this);

    //Validar si no se muestra el div ejecute la peticion ajax, si es visible el div solo ocultelo
    if($(objElement.data('toggle')).css('display') == 'block'){
        slideForms(objElement);
    }else{
        // ajax parameters: url, Method, data, Function done, Function error(optional)
        ajax(
            $(this).attr('data-route'),
            'GET',
            null,
            (res) => {
                if(res.cita){
                    showAppointment(res.cita);
                }else if(res.agenda){
                    $.each(res.agenda, function(key, value){
                        if(key.substr(-4) == 'date'){
                            value = moment(value, 'YYYY-MM-DD').format('dddd D MMMM YYYY');
                        }
    
                        $('#cell-'+key).html(value);
                    });
    
                }
                slideForms(objElement);
            },
            (res) => {
                if(res.status == 403){
                    $('.msgError').html('');
                    $('.msgError').append(alert('danger', res.responseJSON.message));
                }
            }
        );
    }
});

function showAppointment(Cita){
    $('#cell-request_date').html(moment.tz(Cita.request_date.replace(' ', 'T')+'Z', moment.tz.guess()).format('MMMM DD YYYY, h:mm:ss a'));
    $('#cell-inspectionType').html(Cita.inspection_subtype.inspection_types.name);
    $('#cell-inspectionSubtype').html(Cita.inspection_subtype.name);
    $('#cell-client').html(Cita.client.user.name);
    $('#cell-contract').html(Cita.contract.name);

    if(Cita.appointment_states_id != 1){
        $('#cell-inspector').html(Cita.inspector.user.name).parent().show();
        $('#cell-assignment_date').html(moment.tz(Cita.assignment_date.replace(' ', 'T')+'Z', moment.tz.guess()).format('MMMM DD YYYY, h:mm:ss a')).parent().show();
        $('#cell-estimated_start_date').html(moment(Cita.estimated_end_date, 'YYYY-MM-DD').format('dddd D MMMM YYYY')).parent().show();
        $('#cell-estimated_end_date').html(moment(Cita.estimated_start_date, 'YYYY-MM-DD').format('dddd D MMMM YYYY')).parent().show();
    }else{
        $('#cell-inspector').empty().parent().hide();
        $('#cell-assignment_date').empty().parent().hide();
        $('#cell-estimated_start_date').empty().parent().hide();
        $('#cell-estimated_end_date').empty().parent().hide();
    }
}

// Ajax para formulario de editar agendas y citas
$(document).on('click', '.editCalendar', function(e){
    var objElement = $(this);

    //Validar si no se muestra el div ejecute la peticion ajax, si es visible el div solo ocultelo
    if($(objElement.data('toggle')).css('display') == 'block'){
        slideForms(objElement);
    }else{
        // ajax parameters: url, Method, data, Function done, Function error(optional)
        ajax(
            $(this).attr('data-route'),
            'GET',
            null,
            (res) => {
                if(objElement.data('toggle') == '#editAgenda')
                {
                    var aFields = ['start_date', 'end_date'];

                    //Se rellena el formulario de editar con los valores correspondientes
                    $.map(aFields, function(nomField){
                        $('#modalEditDel #'+nomField).val(res.agenda[nomField]);
                    });

                    //Actualización de campos select
                    $('#modalEditDel #edit-inspector_id').val(res.agenda.inspector_id).trigger('change');  
                    $('#modalEditDel #edit-city_id').val(res.agenda.city_id).trigger('change');
                    $('#modalEditDel #edit-country').val(res.agenda.city.countries_id);

                    $('#editAgenda').attr('action', $('#url').val()+'/'+res.agenda.slug);

                    slideForms(objElement, () => {
                        $('#modalEditDel #edit-country').trigger('change',res.agenda.city_id);
                    });

                }
                else if(objElement.data('toggle') == '#editAppointment')
                {
                    var aFields = ['start_date', 'end_date'];

                    //Se rellena el formulario de editar con los valores correspondientes
                    $.map(aFields, function(nomField){
                        $('#modalEditDel #'+nomField).val(res.cita[nomField]);
                    });

                    $('#modalEditDel #edit-inspector_id').val(res.cita.inspector_id).trigger('change');

                    $('#editAppointment').attr('action', $('#url').val()+'/'+res.cita.id);

                    slideForms(objElement);
                }
            },
            (res) => {
                if(res.status == 403){
                    $('.msgError').html('');
                    $('.msgError').append(alert('danger', res.responseJSON.message));
                }
            }
        );
    }
});

function limpiarForm(startDate, endDate, form, fielDate, select){

    $('.form-group').removeClass('has-error');
    $('.errors').empty();

    if (!endDate) endDate = startDate;
    $('.msgError').html('');
    $(form)[0].reset();
    $(form+' #'+fielDate+'start_date').val(startDate);
    $(form+' #'+fielDate+'end_date').val(endDate);
    $(form+' .input-body.select2').trigger('change');
    // $(form+' '+select).html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
    $('#city_id').trigger("change");
}

function verifyInspector()
{
    var idInspector = $(this).val();
    if(idInspector != "")
    {
        $.ajax({
            type: "GET",
            url:  window.Laravel.url+"/ajxVerifyInspector",
            dataType:'json',
            data: {idInspector:idInspector}
            }).done(function( response)
                {
                    if(!jQuery.isEmptyObject(response.notificacion))
                    {
                        renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal',response.notificacion);
                        $('#id_inspector').val(response.data[0].id);
                        $('#nombre_inspector').val(response.data[0].user.name);
                        $('#profession_id').val(response.data[0].profession_id);
                        $('#inspector_type_id').val(response.data[0].inspector_type_id);
                        $('#telefono_inspector').val(response.data[0].phone);
                        $('#direccion_inspector').val(response.data[0].addres);
                        $('#correo_inspector').val(response.data[0].user.email);
                    }
                    else
                    {
                        $('#id_inspector').val("");
                        $('#nombre_inspector').val("");
                        $('#profession_id').val("");
                        $('#inspector_type_id').val("");
                        $('#telefono_inspector').val("");
                        $('#direccion_inspector').val("");
                        $('#correo_inspector').val("");
                    }
                }
            );
    }
}

function guardarHtml(e) {
    e.preventDefault();
    camposLlenos();
    var contenedorHtml = $('#contenedor_formato').html();
    if($('#contenedor_formato').css('display') == 'none'){
      var contenedorHtml = $('#plantilla_formato').html();
    }

    $('#format_expediction').val(contenedorHtml);
    $('#plantilla_formato').css('display','none');
    $('#form_expediction').submit();
}

function camposLlenos() {
    $('body').find('input').each(function(e){
    let objInput = $(this);
    if(objInput.val() != '') {
      objInput.attr('value',objInput.val());
    }
});

$('body').find('textarea').each(function(e){
    let objInput = $(this);
    if(objInput.val() != '') {
      var valor = objInput.val();
      objInput.html('');
      objInput.val('');
      objInput.append(valor);
    }
});

$('body').find(':checkbox').each(function(e){
    let objInput = $(this);
    if(objInput.is(":checked")) {
      objInput.attr('checked','checked');
    }
});

$('body').find(':radio').each(function(e){
    let objInput = $(this);
    if(objInput.is(":checked")) {
      objInput.attr('checked','checked');
    }
  });
}

function deshabilitarCampos(){
    $('#state').val('2');
    $('#plantilla_formato').find('input, textarea, button, select').prop('disabled',true);

}

function calendar(obj){
    $("#calendar").fullCalendar({
        selectable: true,//Permite seleccionar
        nowIndicator: true,//Indicador del tiempo actual
        eventLimit: true, //Para que aparezca "ver más" en caso de muchas citas
        displayEventTime: false,//Para que no aparezca la fecha en el titulo
        contentHeight: 'auto', //Height auto
        customButtons: obj.customButtons,
        header:{
            "left":"prev,next today,createButton",
            "center":"title",
            "right":"month,listMonth"
        },
        events: obj.events,
        eventClick: obj.eventClick,
        select: obj.select,
        dayClick: obj.dayClick,
        editable: true,
        eventDrop: obj.eventDrop,
        eventDragStart: obj.eventDragStart,
        eventDragStop: obj.eventDragStop,
        /* eventAfterAllRender: function (){
            $('.fc-next-button, .fc-prev-button').on('click', colorearAgendas);
        }, */
        viewRender: function(view, element) {
            colorearAgendas();
        }
        // themeSystem: 'bootstrap4'$('#calendar').fullCalendar('renderEvents', vectorEventos);
    });
}

function fillSelect(url, select, edit, funcRes){

    // Se valida si la variable edit es numerica, si no lo es asignele undefined
    if( !$.isNumeric(edit) ) edit = undefined;

    $(select+'_loading').css('display', 'inline-block');

    // ajax parameters: url, Method, data, Function done, Function error(optional)
    ajax(
        url,
        'GET',
        {_token: $('#_token').val()},
        (res) => {
            $(select).empty();

            $.each(res.status, function( key, value )
            {
                $(select).append('<option value="'+value.id+'">'+value.name+'</option>');
            });

            if(edit){
                $(select).val(edit);
            }

            $(select+'_loading').hide();

            if(funcRes) funcRes();
        }
    );
}

function llenarCabeceraFormato()
{
    var preformato = $(this).val();
    var select = $('#cliente_formato').val();
    var company = $('#company_formato').val();
    if($('#format_preformato').val() == ''){
        $('#contenedor_formato').empty();
        $('#contenedor_formato').hide();
    }else if(select != "")
    {
        // ajax parameters: url, Method, data, Function done, Function error(optional)
        ajax(
            window.Laravel.url+"/ajxllenarCabeceraFormato",
            'GET',
            {select:select, company:company, preformato:preformato},
            (response) => {
                console.log(response);
                if(!jQuery.isEmptyObject(response))
                {
                    console.log(response.error);
                    if (response.error != null)
                    {
                        swal({
                        title: response.error,
                        type: 'warning',
                        animation: false,
                        customClass: 'animateErrorIcon '
                        });
                        $('#boton_guardar_html').attr("disabled", true);
                    } else {
                        var html_plantilla_formato = response.preformato.format;
                        if( preformato != '')
                        {
                            $('#boton_guardar_html').attr("disabled", false);

                            if(preformato == 1)
                            {
                                //var plantilla_formato = $('#plantilla_formato').clone();
                                html_plantilla_formato = html_plantilla_formato.replace('*company*',response.company.name);
                                html_plantilla_formato = html_plantilla_formato.replace('*company_logo*',response.company.image);
                                html_plantilla_formato = html_plantilla_formato.replace('*iso_logo*',response.company.iso);
                                html_plantilla_formato = html_plantilla_formato.replace('*client*',response.client.name);
                                html_plantilla_formato = html_plantilla_formato.replace(/\*contract\*/g,response.contract.name);
                                html_plantilla_formato = html_plantilla_formato.replace('*date_contract*',response.contract.date);
                                html_plantilla_formato = html_plantilla_formato.replace('*date_contractual*',response.contract.date);
                                html_plantilla_formato = html_plantilla_formato.replace('*project*','Proyecto Prueba');
                                html_plantilla_formato = html_plantilla_formato.replace('*num_page*',' ');
                                html_plantilla_formato = html_plantilla_formato.replace('*tot_pages*','');
                            }

                            $('#contenedor_formato').html(html_plantilla_formato);
                            $('#contenedor_formato').show();
                        } else {
                            $('#plantilla_formato').css('display','none');
                            $('#contenedor_formato').css('display','none');


                        }
                    }
                }
            }
        );
        
    }
}

function limpiarFormulario()
{
    $('#format_preformato').val('');
    $('#plantilla_formato').css('display','none');
    $('#contenedor_formato').css('display','none');
}

function cargarSelectClients()
{
    var company = $('#company_formato').val();
    if(company != '')
    {
        $.ajax({
            type: "GET",
            url: window.Laravel.url+"/ajxcargarSelectClients",
            dataType:'json',
            data: {company:company}
            }).done(function(response)
            {
        var select = '<select name="client_id" id="cliente_formato" class="input-body">';
                        select +='<option selected="selected">'+response.ChooseOption+'</option>';
        $.map(response.clients, function(name, id)
        {
            select += '<option value="'+id+'">'+name+'</option>';
        });
        select+= '</select>';
        $('#contenedor_client').empty();
        $('#contenedor_client').html(select);
        $('#format_preformato').val('');
        $('#cliente_formato').change(limpiarFormulario);
        $('#format_preformato').change(llenarCabeceraFormato);
        $('#plantilla_formato').css('display','none');
        $('#contenedor_formato').css('display','none');

            });
    }
}

// Campo selector de iconos

$('#icon').removeAttr('disabled');

$('#icon').on('focus', function(e){
    $(".oculto").fadeIn("fast");
});

$('#icon').on('blur', function(e){
    $(".oculto").fadeOut("fast");
});

$(document).on("click",".oculto ul li",function()
{
    $(".inputpicker").val($(this).find("i").data("icon"));
    $('.picker .input-group-addon').html('<i class="fa '+$(this).find("i").data("icon")+'"></i>');
    $('#icon-hidden').val($(this).find("i").data("icon"));
    $(".oculto").fadeOut("fast");
});

// Al realizar la busqueda muestre los iconos resultantes, si no hay coincidencias muestre un mensaje y si vacia la busqueda deseleccione el icono
$(document).on("keyup", '#icon', function()
{
    var value=$(this).val();

    if(value == '')
    {
        $('.picker .input-group-addon').html('<i class="fa fa-hashtag"></i>');
        $('#icon-hidden').val('');
    }
    $('.oculto ul li i').each(function()
    {
        if ($(this).data('icon').search(value) > -1) $(this).closest("li").show();
        else $(this).closest("li").hide();
    });
    if($('.oculto ul li i').is(":visible"))
    {
        $('.oculto #text').empty();
    }else{
        $('.oculto #text').html(chosenText+' '+value);
    }
});

// Cuando clickee en el boton del icono oculte o muestre los iconos
$('.form-group.picker .input-group-addon').on('click', function(){
    if($('.oculto').is(":visible"))
    {
        $(".oculto").fadeOut("fast");
    }
    else
    {
        $(".oculto").fadeIn("fast");
    }
});

function ajax(url, type, data, funcDone, funcError)
{
    $.ajax({
        url: url,
        type: type,
        dataType:'json',
        data: data,
    })
    .done(function(res){
        funcDone(res)
    })
    .fail(function(res){
        console.log('error\n');
        console.log(res);
    })
    .error(function(res){
        if(funcError) funcError(res);
    });
}