var language;
var selectStatusDriver;
$(function() {
	$.post(baseURL + '/' + isoPais + '/trayectos/modelo', { way: 'drivers', modelo: 'driver' })
        .done( function(data) {
            lang = data.language;
            switch (data.code) {
                case 0:
                case 1:
                    var jsonData = data.msg;
                    var table = $('#novo-table').DataTable({
											"drawCallback": function (data) {
												if (data.length === 0 && data.code !== 0) {
													$('#down-report').css('display', 'none');
												} else {
													$('#down-report').css('display', '');
												}
											},
                        select: false,
                        dom: 'Bfrtip',
                        "lengthChange": false,
                        "pagingType": "full_numbers",
                        "pageLength": 5, //Cantidad de registros por pagina
                        "language": { "url":  baseCDN + '/media/js/combustible/Spanish.json'}, //Lenguaje: español //cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json
											buttons: [
												{
													text: '<span id="down-excel" aria-hidden="true" class="icon" data-icon="&#xe05a"></span><select class="select" id="select-drivers"><option value="">Todos</option><option value="1">Activo</option><option value="0">Inactivo</option></select>',
													className: 'down-report',
													titleAttr: lang.TAG_DWN_EXCEL
												}
											],
												data: jsonData, //Arreglo con los  valores del objeto
                        columns: [
                            {
                                title: "Identificación",
                                data:'id_ext_per'
                            },
                            {
                                title: "Nombre completo",
                                data: 'nombreCompleto'
                            },
                            {
                                title: "Usuario",
                                data:'userName'
                            },
                            {
                                title: "Estado",
                                data:'userName',
                                "render": function(userName) {//parámetros adicionales (type,row,meta)
                                    var status;
                                    $.each(jsonData, function(index, gruopObject){
                                        if (userName == gruopObject.userName) {
                                            status = gruopObject.estatus;
                                        }
                                    });
                                    return status == 1 ? 'activo' : 'inactivo';
                                }
                            },
                            {
                                title: "Acciones",
                                data:'userName',
                                "render": function(userName) {//parámetros adicionales (type,row,meta)
                                    return '<a id="editar" data-id="' + userName + '" title="Editar"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe08f;"></span></a>'
                                }
                            }
                        ]
                    });
                    break;
                case 2:
                    $('#msg-info').append('<p class="agrups">'+ data.msg +'</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'y')
                        .text(language.TAG_ACCEPT);
                    notiSystem(data.title);
                    break;
                case 3:
                    $('#msg-info').append('<p class="agrups">'+ data.msg +'</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'c')
                        .text(language.TAG_ACCEPT);
                    notiSystem(data.title);
                    break;
            }
            $('#add').prop('disabled', false);
            $('#loading').hide();
        });

    $('#add').on('click', function () {

        $('#msg-info').append('<input type="text" id="userID" maxlength="9">');
        $('#send-info').text(language.TAG_SEND);
        $('#close-info')
        .addClass('button-cancel')
        .text(language.TAG_CANCEL);
        notiSystem(language.DRIVER_SEARCH_DNI);
    });

});

$('#novo-table').on('click','#editar',function () {

    var userName = $(this).data("id"),
        func = 'update';
    addEdit(userName, func);
});

$('#send-info').on('click', function(){
    var func = $(this).attr('func'),
        dniAction = [{
                'user': $('#userID').val(),
                'name': $('#userName').val(),
                'action': func
            }],
        onlynum = /^[0-9]*$/,
        tamano = /^[\S]{6,9}$/,
        zeroInit = /^0/,
        validaOnlyNum = onlynum.test(dniAction[0].user),
        validaTamano = tamano.test(dniAction[0].user),
        validaZeroInit = zeroInit.test(dniAction[0].user),
        dniValido = false;

        if(!validaOnlyNum) {
            $('#msg').text('DNI: sólo números');
        } else if(validaZeroInit) {
            $('#msg').text('DNI: no puede iniciar con \'0\'');
        } else if (!validaTamano) {
            $('#msg').text('DNI: min 6, max 9 números');
        } else {
            dniValido = true;
        }

    if(dniValido || func == 'register') {
        clearInput();
        $.ajax({
            url: baseURL + '/' + isoPais + '/trayectos/modelo',
            type: 'POST',
            data: {way: 'checkUSER', data: dniAction, modelo: 'driver'},
            datatype:'json',
            beforeSend: function(data){
                $('#send-info, #close-info').text('');
                $('#msg-info').empty();
                $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
            },
            success: function(data) {

                $('#msg-info').empty();
                $('#send-info, #close-info').text('');
							var user = data.msg,
								message;

							lang = data.language;

                switch (data.code) {
                    case 0:
                        message =
                            '<p class="agrups">' + lang.DRIVER_WISH_REG + '</p>'+
                            '<p class="agrups"><strong>' + user.name + '</strong></p>'+
                            '<p class="agrups">' + lang.DRIVER_LIST + '</p>'+
                            '<input type="hidden" id="userID" value="' + user.user + '">'+
                            '<input type="hidden" id="userName" value="' + user.name + '">';
                        $('#msg-info').append(message);
                        $('#send-info')
                            .attr('func', 'register')
                            .text(language.TAG_ACCEPT);
                        $('#close-info')
                        .addClass('button-cancel')
                        .text(language.TAG_CANCEL);
                        notiSystem(language.DRIVER_INCLUDE);
                        break;
                    case 1:
                        message =
                            '<p class="agrups">' + user.name + '</p>'+
                            '<p class="agrups">' + lang.DRIVER_ALREADY_REG + '</p>';
                        $('#msg-info').append(message);
                        $('#close-info')
                        .removeClass('button-cancel')
                        .text(language.TAG_ACCEPT);
                        notiSystem(language.DRIVER_INCLUDE);
                        break;
                    case 2:
                        message =
                            '<p class="agrups"><strong>' + user.driver + '</strong></p>'+
                            '<p class="agrups">' + lang.DRIVER_REGISTER_OK + '</p>';
                        $('#send-info').attr('func', 'search');
                        $('#msg-info').append(message);
                        $('#close-info')
                            .removeClass('button-cancel')
                            .attr('finish', 'y')
                            .text(language.TAG_ACCEPT);
                        notiSystem(language.DRIVER_INCLUDE);
                        break;
                    case 3:
                        $('#msg-system').dialog('close');
                        var userName = user.dni,
                            func = 'register';
                        addEdit(userName, func);
                        break;
                    case 4:
                        $('#msg-info').append('<p class="agrups">'+ data.msg +'</p>');
                        $('#close-info')
                            .removeClass('button-cancel')
                            .attr('finish', 'y')
                            .text(language.TAG_ACCEPT);
                        notiSystem(data.title);
                        break;
                    case 5:
                        $('#msg-info').append('<p class="agrups">'+ data.msg +'</p>');
                        $('#close-info')
                            .removeClass('button-cancel')
                            .attr('finish', 'c')
                            .text(language.TAG_ACCEPT);
                        notiSystem(data.title);
                        break;
                }
            },
            error: function(error){
                console.log(error);
            }
        });
    } else {
        $('#userID')
            .addClass('field-error')
            .focus();
        $('#msg').addClass('field-error');
    }
});

function notiSystem (title, size, type, message) {

    var msgSystem = $('#msg-system');
    $(msgSystem).dialog({
        title: title,
        modal: 'true',
        width: '210px',
        draggable: false,
        rezise: false,
        open: function(event, ui) {
            $('.ui-dialog-titlebar-close', ui.dialog).hide();
        }
    });
    $('#close-info').on('click', function(e){
        e.preventDefault();
        $(msgSystem).dialog('close');
        clearInput();
        $('#msg-info').empty();
        var finish = $(this).attr('finish');
        $('#send-info').attr('func', 'search');
        switch (finish) {
            case 'y':
                location.reload(true);
                break;
            case 'c':
                window.location.replace(baseURL + '/' + isoPais + '/logout');
                break;
        }
    });
}

function addEdit(userName, func) {
    $('form#formulario').empty();
    $('form#formulario').append('<input type="hidden" name="modelo" value="driver" />');
    $('form#formulario').append('<input type="hidden" name="function" value="' + func + '" />');
    $('form#formulario').append('<input type="hidden" name="data-id" value="' + userName + '" />');
    $('form#formulario').attr('action',baseURL+'/'+isoPais+'/trayectos/conductores/perfil');
    $('form#formulario').submit();
}

function clearInput()
{
    $('#userID').removeClass('field-error');
    $('#msg')
        .removeClass('field-error')
        .text('');
}

//Descargar reporte en EXCEL
$('#table-drivers').on('click', '#down-excel', function (e) {
	e.preventDefault();
	var dataReport = {
		status: $('#select-drivers').val()
	}
	downReports('ConductoresExcel', 'reportes_trayectos', dataReport, 'conductores-xls');

});

