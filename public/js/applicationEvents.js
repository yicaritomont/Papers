// console.log('URL desde laravel '+window.Laravel.url);
$(window).ready(inicial);

function inicial (argument)
{
     //Eventos de los botones para solicitud de turno cliente interno
    $('#password_update').keyup(verifyPassword);
    $('#password-confirm').blur(verifyPassword);
    $('#identificacion_inspector').blur(verifyInspector);
    $('#boton_guardar_html').click(guardarHtml);
    $('#boton_firmar_formato').click(deshabilitarCampos);
    $('#boton_firmar_formato').click(guardarHtml);
    $('#company_formato').change(cargarSelectClients);
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

    $(".chosen-select").chosen({
        no_results_text: chosenText,
    });

    // if(window.Laravel.language == 'es') datePickerObj.language = 'es';

    $('.input-group.date').datepicker(datePickerObj);

    //Para que no permita seleccionar un dia antertior al actual
    datePickerObj.startDate = new Date();

    $('.input-group.date-range-inputs input').datepicker(datePickerObj);

    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        startDate: new Date()
    })

    /* console.log($(window).height());
    console.log('Alto: '+$('.container.body').height());

    console.log(window.innerHeight);
    console.log($(window).outerHeight()); */

    $('.right_col>.row').css('margin-top', $('.nav_menu').height()+'px');
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

//Todos los select que requieran una petición ajax para llenar otro select
$('#company_id').on('change', function(event, edit){
    fillSelect(window.Laravel.url+'/companies/clients/'+$(this).val(), '#client_id', edit);
});

$('.inspection_type_id').on('change',function(event, edit){
    fillSelect(window.Laravel.url+'/inspectiontypes/subtypes/'+$(this).val(), '.inspection_subtype_id', edit);
});

$('.country').on('change',function(event, edit){
    // Se valida si la variable edit es numerica, si no lo es asignele undefined
    if( !$.isNumeric(edit) )
    {
        edit = undefined;
    }
    fillSelect(window.Laravel.url+'/country/cities/'+$(this).val(), '.city_id', edit);
});

function setDataTable(targets){
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
    console.log('Ruta absoluta '+rutaAbsoluta);
    console.log('URL '+url);
    
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

//Retorna los mensajes de alerta en base al
function alert(color, msg){
    return '<div class="alert alert-'+color+' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+msg+'</div>';
}

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

$(window).resize(function(){
    changeTopToast();
    $('.right_col>.row').css('margin-top', $('.nav_menu').height()+'px');
    // $('.dataTable').DataTable().columns.adjust().draw();
});

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
$(document).on('submit','.formDelete',function(e){
    console.log($(this).attr('action'));
    e.preventDefault();

    console.log($(this).serialize());

    $.ajax({
        url:$(this).attr('action'),
        type:'POST',
        data:$(this).serialize(),
    })
    .done(function(res){
        var res = JSON.parse(res);

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
    })
    .fail(function(res){
        console.log('error\n'+res);
        console.log(res);
    })
    .error(function(res){
        console.log(res.status);
        if(res.status == 403){
            toast({
                type: 'error',
                title: res.responseJSON.message
            });
            changeTopToast();
        }
    });
});

// Ajax para los formularios actualizar y eliminar de los calendarios
$(document).on('submit','.formCalendar',function(e, salida, revertFunc){
    var idForm = $(this).attr('id');
    var modal = this.dataset.modal;
    if(revertFunc){
        var datos = $('#'+idForm).serialize()+'&drop=drop';
    }else{
        var datos = $('#'+idForm).serialize();
    }

    e.preventDefault();

    $.ajax({
        url:$(this).attr('action'),
        type:'POST',
        data:datos,

    })
    .done(function(res){
        var res = JSON.parse(res);

        console.log(res);

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
    })
    .fail(function(res){
        console.log('fail\n'+res);
        console.log(res);
    })
    .error(function(res){
        console.log(res.status);
        if(res.status == 403){
            $('.msgError').html('');
            $('.msgError').append(alert('danger', res.responseJSON.message));
        }else if(res.status == 422){

            $('.form-group').removeClass('has-error');
            $('.errors').empty();

            $('#'+idForm).find(':input').each(function(){
                var idInput = $(this).attr('id');

                if(idInput !== undefined && res.responseJSON.errors[idInput] !== undefined){
                    $(this).parents('.form-group').addClass('has-error');
                    $(this).parents('.form-group').find('.errors').append(spanError(res.responseJSON.errors[idInput][0]));
                }
            });
        }
    });
});

function spanError(error){
    return '<p class="help-block">'+error+'</p>';
}

// Ajax para ver agendas y citas
$('.showCalendar').on('click', function(e){
    var objElement = $(this);

    //Validar si no se muestra el div ejecute la peticion ajax, si es visible el div solo ocultelo
    if($(objElement.data('toggle')).css('display') == 'block'){
        slideForms(objElement);
    }else{
        $.ajax({
            url:$(this).attr('data-route'),
            type:'GET',
        })
        .done(function(res){
            var res = JSON.parse(res);

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
        })
        .fail(function(res){
            console.log('error\n'+res);
        })
        .error(function(res){
            if(res.status == 403){
                $('.msgError').html('');
                $('.msgError').append(alert('danger', res.responseJSON.message));
            }
        });
    }
});

function showAppointment(Cita){
    $('#cell-request_date').html(moment.tz(Cita.request_date.replace(' ', 'T')+'Z', moment.tz.guess()).format('MMMM DD YYYY, h:mm:ss a'));
    $('#cell-inspector').html(Cita.inspector.user.name);
    $('#cell-inspectionType').html(Cita.inspection_subtype.inspection_types.name);
    $('#cell-inspectionSubtype').html(Cita.inspection_subtype.name);
    $('#cell-client').html(Cita.client.user.name);
    $('#cell-contract').html(Cita.contract.name);

    if(Cita.appointment_states_id != 1){
        $('#cell-assignment_date').html(moment.tz(Cita.assignment_date.replace(' ', 'T')+'Z', moment.tz.guess()).format('MMMM DD YYYY, h:mm:ss a')).parent().show();
        $('#cell-estimated_start_date').html(moment(Cita.estimated_end_date, 'YYYY-MM-DD').format('dddd D MMMM YYYY')).parent().show();
        $('#cell-estimated_end_date').html(moment(Cita.estimated_start_date, 'YYYY-MM-DD').format('dddd D MMMM YYYY')).parent().show();
    }else{
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
        $.ajax({
            url:$(this).attr('data-route'),
            type:'GET',
        })
        .done(function(res){
            var res = JSON.parse(res);

            if(objElement.data('toggle') == '#editAgenda')
            {
                var aFields = ['start_date', 'end_date', 'inspector_id'];

                //Se rellena el formulario de editar con los valores correspondientes
                $.map(aFields, function(nomField){
                    $('#modalEditDel #'+nomField).val(res.agenda[nomField]);
                });

                $('#modalEditDel #country').val(res.agenda.city.countries_id);
                $('#modalEditDel #country').trigger("chosen:updated");
                $('#editAgenda').attr('action', $('#url').val()+'/'+res.agenda.slug);

                slideForms(objElement, () => {
                    $('#modalEditDel #country').trigger('change',res.agenda.city_id);
                });

            }
            else if(objElement.data('toggle') == '#editAppointment')
            {
                var aFields = ['inspector_id', 'start_date', 'end_date'];

                //Se rellena el formulario de editar con los valores correspondientes
                $.map(aFields, function(nomField){
                    $('#modalEditDel #'+nomField).val(res.cita[nomField]);
                });

                $('#editAppointment').attr('action', $('#url').val()+'/'+res.cita.id);

                slideForms(objElement);
            }
        })
        .fail(function(res){
            console.log('error\n'+res);
        })
        .error(function(res){
            if(res.status == 403){
                $('.msgError').html('');
                $('.msgError').append(alert('danger', res.responseJSON.message));
            }
        });
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
    $('#country').trigger("chosen:updated");
    $('#city_id').trigger("chosen:updated");

    $(form+' '+select).html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
}

$(document).on('click', '.btn-form-slide', function(){ slideForms($(this)) });


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
            "right":"month,agendaWeek,listMonth"
        },
        events: obj.events,
        eventClick: obj.eventClick,
        select: obj.select,
        dayClick: obj.dayClick,
        editable: true,
        eventDrop: obj.eventDrop,
    });
}

function fillSelect(url, select, edit){
    console.log(edit);
    console.log(url);
    console.log(select);

    $.ajax({
        url:url,
        type:'GET',
        data:{
            _token: $('#_token').val(),
        }
    })
    .done(function(res){
        res = JSON.parse(res);

        $(select).empty();

        $.each(res.status, function( key, value )
        {
            $(select).append('<option value="'+value.id+'">'+value.name+'</option>');
        });

        if(edit){
            $(select).val(edit);
        }
        $(select).trigger("chosen:updated");

    })
    .fail(function(res){
        alert('Error.');
    });
}

function llenarCabeceraFormato()
{
    var preformato = $(this).val();
    var select = $('#cliente_formato').val();
    var company = $('#company_formato').val();
    if(select != "")
    {
        $.ajax({
            type: "GET",
            url: window.Laravel.url+"/ajxllenarCabeceraFormato",
            dataType:'json',
            data: {select:select, company:company, preformato:preformato}
            }).done(function(response)
                {
                    if(!jQuery.isEmptyObject(response))
                    {
                      console.log(response.error);
                      if (response.error != null)
                      { swal({
                        title: response.error,
                        type: 'warning',
                        animation: false,
                        customClass: 'animateErrorIcon '
                    });
                    } else {
                      var html_plantilla_formato = response.preformato.format;
                      if( preformato != '')
                      {
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
            });
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
