

$(window).ready(inicial);

function inicial (argument) 
{   
     //Eventos de los botones para solicitud de turno cliente interno
     $('#password_update').keyup(verifyPassword);
     $('#password-confirm').blur(verifyPassword);
     $('#identificacion_inspector').blur(verifyInspector);
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

function verifyInspector()
{
    var idInspector = $(this).val();
    if(idInspector != "")
    {
        $.ajax({                                                    
            type: "GET",
            url: obtenerUrl()+"/public/ajxVerifyInspector",   
            dataType:'json',
            data: {idInspector:idInspector}
            }).done(function( response) 
                {       
                    if(!jQuery.isEmptyObject(response.notificacion))
                    { 
                        renderizarNotificacionModal('modal_notificacion','cont-notificacion-modal',response.notificacion);
                        $('#id_inspector').val(response.data[0].id);
                        $('#nombre_inspector').val(response.data[0].name);
                        $('#profesion_inspector').val(response.data[0].profession_id);
                        $('#tipo_inspector').val(response.data[0].inspector_type_id);
                        $('#telefono_inspector').val(response.data[0].phone);
                        $('#direccion_inspector').val(response.data[0].addres);
                        $('#correo_inspector').val(response.data[0].email);
                    }
                    else
                    {
                        $('#id_inspector').val("");
                        $('#nombre_inspector').val("");
                        $('#profesion_inspector').val("");
                        $('#tipo_inspector').val("");
                        $('#telefono_inspector').val("");
                        $('#direccion_inspector').val("");
                        $('#correo_inspector').val("");
                    }
                }
            );
    }
}
