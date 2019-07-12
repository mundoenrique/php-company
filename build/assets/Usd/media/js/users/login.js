var ingresar_ = function() {
        var user = $("#user_login").val();

        var pass = $("#user_pass").val();

        if (user != '' && pass != '') {
            pass = hex_md5(pass);
            login(user, pass, '');

        } else {
            $('.verifica_sesion').hide();
            if (user == '' && pass == '') {
                $("#user_login").addClass('error-login');
                $("#user_pass").addClass('error-login');
                $("#user_login").attr('placeholder', 'Campo obligatorio');
                $("#user_pass").attr('placeholder', 'Campo obligatorio');

            } else if (pass == '') {
                $("#user_pass").addClass('error-login');
                $("#user_pass").attr('placeholder', 'Campo obligatorio');

            } else if (user == '') {
                $("#user_login").addClass('error-login');
                $("#user_login").attr('placeholder', 'Campo obligatorio');
            }
        }
    },
    login = function(user, pass, active) {

        document.cookie = 'cookie';
        cookie = document.cookie;

        if (user != '' && pass != '' && cookie != '') {

            $('#user_login').attr('disabled', 'true');
            $('#user_pass').attr('disabled', 'true');

            $(".ju-sliderbutton-text").html("Verificando...");

            $(".ju-sliderbutton .ju-sliderbutton-slider .ui-slider-handle").hide();

						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);

            $consulta = $.post(baseURL + isoPais + "/validation", {
                user_login: user,
                user_pass: pass,
								user_active: active,
								ceo_name: ceo_cook
            });

            $consulta.done(function(data) {

                $('.verifica_sesion').hide();

                var user = $("#user_login").val();
                var pass = $("#user_pass").val();

                if (data == "userold") {

                    pass = pass.toUpperCase();
                    pass = hex_md5(pass);
                    login(user, pass, 1);
                    return false;

                } else if (data == "validated") {

                    $(location).attr('href', baseURL + isoPais + "/dashboard");

                } else if (data == "newuser") {

                    $(location).attr('href', baseURL + isoPais + "/terminos");

                } else if (data == "caducoPass") {

                    $(location).attr('href', baseURL + isoPais + '/clave');

                } else if (data == 'conectado') {
                    $('<div><h6>Tu última sesión se cerró de manera incorrecta. Ten en cuenta que para salir de la aplicación debe seleccionar <strong>"Salir"</strong>. <h4>Pulse "Aceptar" para continuar.<h4></h6></div>')
                        .dialog({
                            title: "Conexión Empresas Online",
                            modal: true,
                            maxWidth: 700,
                            maxHeight: 300,
                            resizable: false,
                            close: function() {
                                $(this).dialog("destroy");
                                habilitar();
                            },
                            buttons: {
                                Aceptar: function() {

																		var ceo_cook = decodeURIComponent(
																			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
																		);

                                    $.post(baseURL + isoPais + "/logout", {
																				'data-user': user,
																				ceo_name: ceo_cook
                                    });
                                    $(this).dialog("destroy");
                                    habilitar();

                                }
                            }
                        });
                } else {

                    data.toLowerCase().indexOf('inactivo') != -1 ? $.balloon.defaults.classname = "login-inactive" : $.balloon.defaults.classname = "error-login-2";


                    //$.balloon.defaults.classname = "login-inactive";

                    $.balloon.defaults.css = null;

                    $("#user_login").showBalloon({
                        position: "left",
                        contents: data
                    });

                    setTimeout(function() {

                        $("#user_login").hideBalloon();
                        habilitar();

                    }, 3000);
                }
                $("#user_pass").val('');

            })

        } else {
            $('.verifica_sesion').hide();
            if (cookie == '') {
                $('<div><h5>La funcionalidad de cookies de tu navegador se encuentra desactivada.</h5><h4>Por favor vuelve activarla.</h4></div>').dialog({
                    title: "Conexión Empresas Online",
                    modal: true,
                    maxWidth: 700,
                    maxHeight: 300,
                    resizable: false,
                    close: function() {
                        $(this).dialog("destroy");
                    },
                    buttons: {
                        Aceptar: function() {
                            $(this).dialog("destroy");
                        }
                    }
                });
            }
        }

    },
    habilitar = function() {
        $("#user_login").removeAttr('disabled');
        $("#user_pass").removeAttr('disabled');
        $(".ju-sliderbutton-text").html("Desliza para ingresar");
        $(".ju-sliderbutton .ju-sliderbutton-slider .ui-slider-handle").show();
    },
    marcarError = function(msj) {
        $.balloon.defaults.classname = "error-login-2";
        $.balloon.defaults.css = null;
        $("#user_login").showBalloon({
            position: "left",
            contents: msj
        }); //mostrar tooltip
        $('#sliderbutton-login').sliderbutton("disable");
        setTimeout(function() {
            $("#user_login").hideBalloon({
                position: "left",
                contents: msj
            });
        }, 3000); // ocultar tooltip
    },
    validarAlfanumerico = function($elem) {

        var texto = $elem.val();

        if (texto.match(/[a-zA-Z0-9]$/) == null) {
            return false;
        } else {
            return true;
        }

    },
    maxchars = 15,
    limit = false;

$(function() {

    $('.kwicks').kwicks({
        size: 135,
        maxSize: 250,
        spacing: 5,
        behavior: 'menu'
    });


    /*Inicializar sliderbutton*/

    $('#sliderbutton-login').sliderbutton({
        text: "Desliza para ingresar",
        activate: function() {
            ingresar_();
        }
    });

    $('#button-login').on('click', function() {
        $('.verifica_sesion').show();
        ingresar_();
    });

    $('.ui-slider-handle').append('<p class="flecha"> >> </p>');

    $('#user_login').on('keyup', function(ev) {

        if ($("#user_login").val().length < maxchars) {
            limit = false;
        }
        if (limit) {
            marcarError("Máximo " + maxchars + " caracteres");
        }

        if ($("#user_login").val().length == maxchars) {
            limit = true;
            $("#user_login").attr('maxlength', maxchars);
        }


        if ($(this) != '') {
            $(this).removeClass('error-login');
        }

        if (validarAlfanumerico($("#user_login"))) {
            $('#sliderbutton-login').sliderbutton("enable");
        } else if ($(this).val() != '' && $(this).val() != undefined) {
            marcarError("Caracter no permitido");
        }

    });

    $('#user_pass').on('keyup', function() {
        if ($(this) != '') {
            $(this).removeClass('error-login');
        }
    });

    //--Fin validar caracteres alfanumericos usuario

    var isMobile = false; //initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;

    if (isMobile) {
        $('#sliderbutton-login').hide();
        $('#login-mobile').show();
    }

});
