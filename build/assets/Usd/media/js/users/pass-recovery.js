var sitio = false;
var modal = false;
var titulo = null;
var mensaje1 = null;
var mensaje2 = null;
$(function(){

    $("fieldset > :input").on("click focus keyup", function(){

        var id = $(this).attr("id");
        $("#"+id).removeClass('error-login');
        $("#"+id).removeAttr('placeholder', 'Campo obligatorio');

    });

    $("#continuar").on("click",function(){

        var userName  = $("#userName").val().trim().toUpperCase();
        var idEmpresa = $("#idEmpresa").val().trim().toUpperCase();
        var email     = $("#email").val().trim().toUpperCase();

        json={};

        json.userName  = userName;
        json.idEmpresa = idEmpresa;
        json.email     = email;

        if(!validar(json)){
            titulo = 'Campos inválidos';
            mensaje1 = 'Por favor <strong>Verifica</strong> los datos, e intenta nuevamente';
            modal = true;
            notificacion(sitio, modal, titulo, mensaje1);
        } else {
            enviar(json);
        }

    });


    var enviar = function(jsonData){
        $aux = $("#loading").dialog({
            title:'Procesando solicitud',
            modal:true,
            close: function(){$(this).dialog('destroy')},
            resizable:false });

						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);

						jsonData.ceo_name = ceo_cook;

        $.post(baseURL + isoPais+'/users/recuperar-pass', jsonData )
            .done(function( data ) {
            if (data == "validate") {
                $aux.dialog('destroy');
                sitio = true;
                modal = true;
                titulo =   '<h4><strong>Solicitud procesada satisfactoriamente.</strong></h4>';
                mensaje1 = 'Tu contraseña a <strong>Conexión Empresas Online</strong> ha sido restablecida.<br><br> <h4>Pulsa \"Aceptar\" para continuar.<h4>';
                notificacion(sitio, modal, titulo, mensaje1);
            } else if (data == "no-companies") {
                $aux.dialog('destroy');
                sitio = true;
                modal = true;
                titulo = '<h3><strong>No se pudo procesar tu solicitud.</strong></h3>';
                mensaje1 = 'El usuario indicado no posee empresa asignada.';
                notificacion(sitio, modal, titulo, mensaje1);
            } else if (data == "general-error") {
                $aux.dialog('destroy');
                sitio = true;
                modal = true;
                titulo = '<h3><strong>Error en sistema.</strong></h3>';
                mensaje1 = 'Disculpa los inconvenientes. Intenta nuevamente más tarde.';
                notificacion(sitio, modal, titulo, mensaje1);
            } else if (data == "error-email") {
                $aux.dialog('destroy');
                sitio = true;
                modal = true;
                titulo = '<h3><strong>No se pudo procesar tu solicitud.</strong></h3>';
                mensaje1 = 'Inconvenientes enviando el email al destinatario. Verifica el email ingresado e intenta nuevamente.';
                notificacion(sitio, modal, titulo, mensaje1);
            }  else {
                data = $.parseJSON(data);
                if (data.rc == -150) {
                    $aux.dialog('destroy');
                    sitio = false;
                    modal = true;
                    titulo = data.title;
                    mensaje1 = '<strong>' + data.msg + '</strong>';
                    notificacion(sitio, modal, titulo, mensaje1);
                } else if (data.rc == -205) {
                    $aux.dialog('destroy');
                    sitio = false;
                    modal = true;
                    titulo = data.title;
                    mensaje1 = '<strong>' + data.msg1 + '</strong>';
                    mensaje2 = data.msg2;
                    notificacion(sitio, modal, titulo, mensaje1);
                } else if (data.rc == -159) {
                    $aux.dialog('destroy');
                    sitio = false;
                    modal = true;
                    titulo = data.title;
                    mensaje1 = '<strong>' + data.msg + '</strong>';
                    notificacion(sitio, modal, titulo, mensaje1);
                }
            }

        });

    }

    function validar(json){

        emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})+$/;
        charRegex = /^([a-zA-ZñÑáéíóúÁÉÍÓÚ]+\s*){1,100}$/;
        rifRegex = /^([\w_\-]+)+$/i;
        alfanumericRegex = /^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ]+\s*){1,100}$/;
        validez=true;

        if( !emailRegex.test(json.email) || json.email == '' ){
            $("#email").addClass("error-login");
            validez=false;
        }
        if( !alfanumericRegex.test(json.userName) || json.userName == "" ){
            $("#userName").addClass("error-login");
            validez=false;
        }

        if( !rifRegex.test(json.idEmpresa) || json.idEmpresa == "" ){
            $("#idEmpresa").addClass("error-login");
            validez=false;
        }

        return validez;
    }

    var notificacion= function(sitio, modal, titulo, mensaje, mensaje2){
        var mostrar = '';
        if (modal) {
            mostrar = '<div style="font-size:14px"><strong style="display:block; margin-top:12px;">'+ titulo +'</strong><br>'+ mensaje +'</div>';
        } else {
            mostrar = '<div style="font-size:14px"><strong style="display:block; margin-top:12px;">'+ titulo +'</strong><br>'+ mensaje +'<br><br>'+ mensaje2 +'</div>';
        }

        $(mostrar).dialog({
            title: "Restablecer contraseña",
            modal: true,
            width: 400,
            maxWidth: 500,
            maxHeight: 300,
            resizable: false,
            close: function() {
                $(this).dialog("destroy");
                if (sitio){
                    $('form')[0].reset();
                    window.location.href= baseURL + isoPais + '/login';

                }

            },
            buttons: {
                Aceptar: function() {
                    $(this).dialog("destroy");
                    if (sitio){
                        $('form')[0].reset();
                        window.location.href= baseURL + isoPais + '/login';

                    }
                }
            }
        });

    }

});//Fin funcion principal
