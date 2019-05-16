//llamado al modal que contiene el formulario de registro o actualización del grupo
function addEdit(id) {
    var title = id != 0 ? lang.GROUP_EDIT : lang.GROUP_ADD;
    if (id != 0){
        $('#send-info, #close-info').text('');
        $('#msg-info').empty();
        $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
				notiSystem('msg', title);

				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
					);

        $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'vehicleGroups', modelo: 'vehicleGroups', data: id, ceo_name: ceo_cook})
            .done( function(response) {
                $('#msg-info').empty();
                switch (response.code) {
                    case 0:
                        var groupData = response.msg;
                        $('#msg-system').dialog('close');
                        $('#idFlota').val(id);
                        $('#nameGroup').val(groupData.name);
                        $('#descGroup').val(groupData.description);
                        $("#send-save").html(lang.TAG_WITHOUT_CHANGES);
                        notiSystem('groups', title);
                        break;
                    case 1:
                        $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                        $('#close-info')
                        .removeClass('button-cancel')
                        .text(lang.TAG_ACCEPT);
                        notiSystem('msg', response.title);
                        break;
                    case 3:
                        $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                        $('#close-info')
                            .removeClass('button-cancel')
                            .attr('finish', 'c')
                            .text(lang.TAG_ACCEPT);
                        notiSystem('msg', resoponse.title);
                        break;
                }
            });
    } else {

        $('#nameGroup').val('');
        $('#descGroup').val('');
        $("#send-save").html(lang.TAG_SEND);

        notiSystem('groups', title);
    }
}

function validar_campos() {

    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    var verify = /^([a-zA-ZáéíóúñÁÉÍÓÚÑ\s0-9]+[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9._-]*)$/;

    $("#formAddEdit").validate({
        errorElement: "label",
        ignore: "",
        errorContainer: "#msg",
        errorClass: "field-error",
        validClass: "field-success",
        errorLabelContainer: "#msg",

        rules: {
            'nameGroup': {required: true, pattern: verify, maxlength: 25},
            'desc': {required: true, pattern: verify, maxlength: 50}
        },

        messages:{
            'nameGroup': {
                required: 'Indique el nombre del grupo',
                pattern: 'El nombre presenta un carácter inválido'
            },
            'desc': {
                required: 'Indique la descripción del grupo',
                pattern: 'La descripción presenta un carácter inválido'
            }
        }
    });

}

//llamado a la lista de vehículos de un grupo
function vehicles (id, name) {
    $('form#formulario').empty();
    $('form#formulario').append('<input type="hidden" name="data-id" value="'+id+'" />');
    $('form#formulario').append('<input type="hidden" name="data-name" value="'+name+'" />');
    $('form#formulario').append('<input type="hidden" name="modelo" value="vehicles" />');
    $('form#formulario').attr('action', baseURL + '/' + isoPais + '/trayectos/vehiculos');
    $('form#formulario').submit();
}
