var language;

var errorResponse = [{
    '-33':'Ocurrió un error general',
    '-3':'Ocurrió un error general',
    '-20':'Ocurrió un error general',
    '-61':'La sesión ha caducado',
    '-241':'Ocurrió un error general',
    '-318':'Ocurrió un error general',
}];


$(function() {
    var asignation = $('#asignacionNumber').val();

    //Devolver cuenta a conductor
    $('#accountOff').click(function () {
        var idAccount = $('#idAccount').val();
        notiSystem ('Cuentas',idAccount);
    });


    if(asignation == 'No disponible'){
        $('#assignContainer').show();

				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);

				$.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'availableDrivers', modelo: 'account', ceo_name: ceo_cook})
            .done(function (response) {
                if(response != ''){
                    $('#driverAvailable').show();
                    $.each(response.msg.lista,function (i,v) {
                        $('#selectDriver').append(
                            ' <option selected value="'+v.userName+'">'+v.primerNombre.toLowerCase()+ ' '+v.primerApellido.toLowerCase()+'</option>'
                        )
                    })
                }else{
                    $('#driverNotAvailable').show();
                    $('#assignAccount').hide();
                }

            });
    }else{
        $('#accountOff').show();
    }


    //Asignar cuenta a conductor
    $('#assignAccount').click(function () {
        var cardNumber = $('#cardNumber').val(),
            userSelected = $('#selectDriver option:selected').val(),
            cardDigit = cardNumber.substring(10);
        // console.log('cuenta: ' + cardDigit + ' usuario seleccionado: ' + userSelected);
        notiSystemAssing('Cuentas',cardDigit,userSelected);
    });

    function notiSystemAssing (title, card, user) {
        var assingData = {
            card : card,
            user : user
        };
        $( "#msg-system-assing" ).dialog({
            title : title,
            modal: 'true',
            width: '210px',
            draggable: false,
            rezise: false,
            closeOnEscape: false,
            // dialogClass: "no-close",
        });

        $('button#send-info').click(function () {
            // console.log('Se asiganara cuenta');

						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);

						$.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'allocatingDriver', modelo: 'account', data: assingData, ceo_name: ceo_cook})
                .done(function (response) {
                    // console.log(response.code);

                    if(response.code == undefined){
                        var jsonResponse = JSON.parse(response);
                        $('#msg-system-assing').text('Cuenta asignada');
                    }
                    else{
                        if(response.msg.code) {
	                        $('#msg-system-assing').text(response.msg.msg);
                        } else {
	                        $('#msg-system-assing').text('Ocurrió un error');
                        }

                    }
	                setTimeout(function () {
		                $('#msg-system-assing').dialog( "close" );
		                window.location = $('#urlAccount').val();
	                },2000);
                });
        });
        $('button#close-info').click(function () {
            $( "#msg-system-assing" ).dialog('close');
        });

    }

    function notiSystem (title,id) {
        $( "#msg-system" ).dialog({
            title : title,
            modal: 'true',
            width: '210px',
            // closeOnEscape: false,
            draggable: false,
            rezise: false,
            open: function(event, ui) {
                $('.ui-dialog-titlebar-close', ui.dialog).hide();
            }
        });

        $('button#send-info').click(function () {

						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);

						$.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'deallocateAccounts', modelo: 'account', data: id, ceo_name: ceo_cook})
                .done(function (response) {
                    $('#msg-system').text('Cuenta devuelta');
                    var jsonResponse = JSON.parse(response);
                    // console.log(jsonResponse.rc);
                    if(jsonResponse != 0){
                        errorMessage(jsonResponse.rc);
                    }
	                setTimeout(function () {
		                $('#msg-system').dialog( "close" );
		                window.location = $('#urlAccount').val();
	                },3000);
                });
        });
        $('button#close-info').click(function () {
            $( "#msg-system" ).dialog('close');
        });
    }

    //Funcion que evalua rc y muestra mensaje de error
    function errorMessage(rc) {
        $.each(errorResponse[0],function (i,v) {
            if(rc == i){
                // console.log(v);
                alert(v);
            }
        })
}
});
