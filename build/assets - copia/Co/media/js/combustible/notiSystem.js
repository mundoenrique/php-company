function notiSystem (action, title, size = '320px') {

    if (action === 'msg') {
        $('#msg-system').dialog({
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
            $('#msg-system').dialog('close');
            $('#msg-info').empty();
						var finish = $(this).attr('finish');
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
							);
            switch (finish) {
                case 'u':
                    location.reload(true);
                    break;
                case 'up':
										$('form#formulario').empty();
										$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
                    $('form#formulario').append('<input type="hidden" name="data-id" value="' + idflota + '" />');
                    $('form#formulario').append('<input type="hidden" name="data-name" value="' + nameFlota + '" />');
                    $('form#formulario').append('<input type="hidden" name="modelo" value="vehicles" />');
                    $('form#formulario').attr('action', baseURL + '/' + isoPais + '/trayectos/vehiculos');
                    $('form#formulario').submit();
                    break;
                case 'g':
                    window.location.replace(baseURL + '/' + isoPais + '/trayectos/gruposVehiculos');
                    break;
                case 'c':
                    window.location.replace(baseURL + '/' + isoPais + '/logout');
                    break;
            }
        });
    } else if (action === 'groups') {
        $('#add-edit').dialog({
            modal: "true",
            width: size,
            resizable: false,
            draggable: false,
            title: title,
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog).hide();
            }
        });
        $('#cancel').click(function(){
            $('#add-edit').dialog("close");
            $('#formAddEdit input, #formAddEdit textarea').removeClass('field-error');
            $('#msg').empty();
            $('#send-save')
                .removeClass('withoutChanges')
                .prop('disabled', false);
        });
    }
}
