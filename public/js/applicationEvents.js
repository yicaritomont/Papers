$(window).ready(inicial);

function inicial (argument) 
{   
     //Eventos de los botones para solicitud de turno cliente interno
     $('#password_update').keypress(verifyPassword);
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
    alert(newPassword);
    if(newPassword != "")
    {
        $('#changePassword').attr('disabled','disabled');
        $.ajax({                                                    
            type: "GET",
            url: obtenerUrl()+"/public/ajxVerifyPassword",   
            dataType:'json',
            data: {newPassword:newPassword , userPassword : userPassword}
            }).done(function( response) 
                {       
                    if(!jQuery.isEmptyObject(response.notificacion))
                    {   
                        renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal',response.notificacion);
                        $('#changePassword').removeAttr('disabled');
                    }
                    else
                    {
                        renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal',response.notificacion);
                    }
                }
            );
    }
}