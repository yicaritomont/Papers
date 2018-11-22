$(window).ready(inicial);

function inicial (argument)
{
     //Eventos de los botones para solicitud de turno cliente interno
     $('#password_update').keyup(verifyPassword);
     $('#password-confirm').blur(verifyPassword);
     $('#identificacion_inspector').blur(verifyInspector);
      //$('.id_country').change(mostrarCiudades);
    $('#boton_guardar_html').click(guardarHtml);

    //Se definen atributos generales de DataTable
    dataTableObject = {
        responsive: true,
        serverSide: true,
    };

    //Se valida el idioma
    if(window.Laravel.language == 'es'){
        dataTableObject.language = {url: window.Laravel.url+'/js/lib/dataTable/Spanish.json'};     
    }

    //Campo fecha

    var datePickerObj = {
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        orientation: "bottom auto",
        forceParse: false,
    };

    if(window.Laravel.language == 'es') datePickerObj.language = 'es';

    $('.input-group.date').datepicker(datePickerObj);

    /* console.log($(window).height());
    console.log('Alto: '+$('.container.body').height());

    console.log(window.innerHeight);
    console.log($(window).outerHeight()); */
}

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

//Ocultar los formularios desplegables y solo mostrar el seleccionado
/* $(document).on('click', 'button[data-toggle]', function(e) {
    console.log('Clickeo');
    var selector = $(this).data('toggle');
    $('.formSlide:not('+selector+')').slideUp('slow');
    $(selector).slideToggle('slow');
}); */
function slideForms(obj) {
    var selector = obj.data('toggle');
    $('.formSlide:not('+selector+')').slideUp('slow');
    $(selector).slideToggle('slow');
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
    .always(function(res){
        console.log('complete\n'+res);
    })
});

// Ajax para los formularios editar y eliminar de los calendarios
$(document).on('submit','.formCalendar',function(e, salida, revertFunc){
    var idForm = $(this).attr('id');
    var modal = this.dataset.modal;
    if(revertFunc){
        var datos = $('#'+idForm).serialize()+'&drop=drop';
    }else{
        var datos = $('#'+idForm).serialize();
    }

    console.log(datos);

    e.preventDefault();                
    
    $.ajax({
        url:$(this).attr('action'),
        type:'POST',
        data:datos,
        
    })
    .done(function(res){
        var res = JSON.parse(res);   

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
});

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
            $(objElement.data('toggle')).html(res.html);
            
            slideForms(objElement);
        })
        .fail(function(res){
            console.log('error\n'+res);
        });
    }
});

// Ajax para editar agendas y citas
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
                $('#modalEditDel #country').trigger('change',res.agenda.city_id);    
                $('#editAgenda').attr('action', $('#url').val()+'/ajax/'+res.agenda.slug);

            }else if(objElement.data('toggle') == '#editAppointment')
            {
                var aFields = ['inspector_id', 'start_date', 'end_date'];
    
                //Se rellena el formulario de editar con los valores correspondientes
                $.map(aFields, function(nomField){
                    $('#modalEditDel #'+nomField).val(res.cita[nomField]);
                });
    
                $('#editAppointment').attr('action', $('#url').val()+'/'+res.cita.id);
            }
            slideForms(objElement);
        })
        .fail(function(res){
            console.log('error\n'+res);
        });
    }
});

//Limpiar el formulario de crear agenda
/* function limpiarForm(startDate, endDate){
    if (!endDate) endDate = startDate;
    $('.msgError').html('');
    $('#formCreateAgenda')[0].reset();
    $('#formCreateAgenda #start_date').val(startDate);
    $('#formCreateAgenda #end_date').val(endDate);
    $('#formCreateAgenda .city_id').html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
} */
function limpiarForm(startDate, endDate, form, fielDate, select){
    if (!endDate) endDate = startDate;
    $('.msgError').html('');
    $(form)[0].reset();
    $(form+' #'+fielDate+'start_date').val(startDate);
    $(form+' #'+fielDate+'end_date').val(endDate);
    $(form+' '+select).html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
}

$(document).on('click', '.btn-form-slide', function(){ slideForms($(this)) });
/*function mostrarCiudades()
{
    var country = $('.id_country').val();
    $.ajax({
        type: "GET",
        url: obtenerUrl() + '/public/ajxCountry',
        datType: 'json',
        data: { country: country }
    }).done(function (response)
    {
        alert(response);
        var select = '<select name="" id="citie_id" class="form-control">'
            $.map(response.citiesCountry,function(name, id)
            {
                select += '<option values="'+id+'">'+name+'</option>'
            });
            select += '</select>';
            $('#container_cities').empty();
            $('#container_cities').html(select);
            $('#ciie_id').chosen({ no_results_text: "No se encuentra" });

    });
}*/

function verifyInspector()
{
    var idInspector = $(this).val();
    alert(idInspector);
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

function guardarHtml() {
  camposLlenos();
  var contenedorHtml = $('#contenedorHtml').html();
  $('#format_expediction').val(contenedorHtml);
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

$('.country').on('change',function(event, city_id){
    $.ajax({
        url:this.dataset.route,
        type:'POST',
        data:{
            id: $(this).val(),
            _token: $('#_token').val(),
        }
    })
    .done(function(res){
        // console.log('done\n'+res);
        res = JSON.parse(res);
        $('.city_id').html('<option selected="selected" value="">'+$("#selectOption").val()+'</option>');
        $('.city_id').append(res);

        if(city_id != undefined){
            $('#modalEditDel #city_id').val(city_id);
        }
    })
    .fail(function(res){
        alert('Error\n'+res);
    });
});

$('.inspection_type_id').on('change',function(event, cita){
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