var serv_var = {
	pgTotal: 1,
	pgActual: 1,
	busk: false,
	paginas: 10,
	paginar: true,
	dni_tarjetas: "",
	noTarjetas: "",
	TotalTjts: 0,
	actualizarDatos: 'hidden',
	consulta: 'hidden',
	bloquear: 'hidden',
	desbloqear: 'hidden',
	entregar: 'hidden',
	enviar: 'hidden',
	recibirBanco: 'hidden',
	recibirEmpresa: 'hidden',
	maestroParam: null,
	cantXdia: null,
	acumXsem: null,
	monto: [],
	saldoDispon: 0,
	montoMin: 0,
	fallidas: 0
}

$('#buscar').on('click', function () {

	var RE = /^\d*$/,
		servicio = $('#servicio'),
		lote = $('#lote'),
		cedula = $('#cedula'),
		tarjeta = $('#tarjeta'),
		validInput = true,
		camposValid = '<div id="validar">';

	if (servicio.val().length == 0 && lote.val().length == 0 && cedula.val().length == 0 && tarjeta.val().length == 0) {
		camposValid += '<p>* Debe diligenciar al menos un campo</p>';
		validInput = false;
	} else {

		if (servicio.val().length > 0 && !RE.test(servicio.val())) {
			camposValid += '<p>* El campo orden de servicio debe ser numerico</p>';
			validInput = false;
			servicio.css('border-color', '#cd0a0a')
		} else {
			servicio.removeAttr('style');
		}

		if (lote.val().length > 0 && !RE.test(lote.val())) {
			camposValid += '<p>* El campo lote debe ser numerico</p>';
			validInput = false;
			lote.css('border-color', '#cd0a0a')
		} else {
			lote.removeAttr('style');
		}

		if (cedula.val().length > 0 && !RE.test(cedula.val())) {
			camposValid += '<p>* El campo cédula debe ser numerico</p>';
			validInput = false;
			cedula.css('border-color', '#cd0a0a')
		} else {
			cedula.removeAttr('style');
		}

		if (tarjeta.val().length > 0 && !RE.test(tarjeta.val())) {
			camposValid += '<p>* El campo tarjeta debe ser numerico</p>';
			validInput = false;
			tarjeta.css('border-color', '#cd0a0a')
		} else {
			tarjeta.removeAttr('style');
		}
	}

	camposValid += '</div>';
	if (!validInput) {
		$(camposValid).dialog({

			dialogClass: "hide-close",
			title: 'Campos inválidos',
			modal: true,
			resizable: false,
			draggable: false,
			open: function (event, ui) {
				$('.ui-dialog-titlebar-close', ui.dialog).hide();
			},
			buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
						$(this).dialog("destroy");
					}
				}
			}
		});
	} else {
		serv_var.busk = true;
		serv_var.TotalTjts = 0;
		buscar(1);
	}
});

// ACCION EVENTO "SELECCIONAR TODOS"
$('#select-allR').on('click', function () {
	if ($(this).is(':checked')) {
		$(':checkbox').each(function () {
			this.checked = 1;
			if ($(this).parents('tr').attr('tjta') != undefined) {
				serv_var.noTarjetas += $(this).parents('tr').attr('tjta') + ",";
				serv_var.dni_tarjetas += $(this).parents('tr').attr('id_ext_per') + ",";
			}
		});
	} else {
		$(':checkbox').each(function () {
			this.checked = 0;
		});
		resett();
	}
});


// BUSCAR TARJETAS PARA TRANSFERENCIA MAESTRA
function buscar(pgSgt) {

	var $aux = $('#loading').dialog({

		dialogClass: "hide-close",
		title: "Buscando tarjetas",
		modal: true,
		resizable: false,
		dialogClass: 'hide-close',
		close: function () {
			$aux.dialog('close');
		},
		position: {
			my: "top"
		}
	});
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);

	var dataRequest = JSON.stringify({
		data_orden: $('#servicio').val(),
		data_lote: $('#lote').val(),
		data_cedula: $('#cedula').val(),
		data_tarjeta: $('#tarjeta').val(),
		data_pg: pgSgt,
		data_paginas: serv_var.paginas,
		data_paginar: serv_var.paginar
	})
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {
		format: CryptoJSAesJson
	}).toString();
	$.post(baseURL + api + isoPais + "/servicios/transferencia-maestra/buscarTarjetas", {
			request: dataRequest,
			ceo_name: ceo_cook,
			plot: btoa(ceo_cook)
		})
		.done(function (response) {
			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
				format: CryptoJSAesJson
			}).toString(CryptoJS.enc.Utf8))

			console.log(data);


		 	$aux.dialog('destroy');
		 if (!data.result.ERROR) {
				$('#resultado-tarjetas').show();

				cargarResultado(data);
				$('#resultado-tarjetas').find('.jPag-sprevious').attr('title', "anterior");
				$('#resultado-tarjetas').find('.jPag-snext').attr('title', "siguiente");

			} else {
				$('#resultado-tarjetas').hide();
				if (data.result.ERROR == '-29') {
					alert('Usuario actualmente desconectado');
					location.reload();
				} else {
					notificacion("Buscando tarjetas", data.result.ERROR);
				}
			}
		});
}


// CARGAR-MOSTRAR TARJETAS OBTENIDAS DE LA PETICION DE BÚSQUEDA

function cargarResultado(data) {

	if (serv_var.busk) {
		serv_var.busk = false;
		$('.table-text-service tbody').empty();
	}

	var tr;
	 serv_var.pgTotal = parseInt(data.result.totalPaginas, 10);
	 serv_var.pgActual = parseInt(data.result.pagina, 10);

	if (data.result.detalleEmisiones.length > 0) {
		serv_var.TotalTjts += data.result.detalleEmisiones.length;
		$('#textS').empty();
		$('#textS').append('<em>Seleccionar todo (' + serv_var.TotalTjts + ' de ' + data.result.totalRegistros + ')</em>');
		$('.table-text-service thead th').css('min-width', '75px');
		$('.table-text-service tbody td').css('min-width', '75px');

		var aumenta = 1;
		var iconos = {'ACTUALIZAR_DATOS':'&#xe02d;',
		'CONSULTA_SALDO_TARJETA':'&#xe022;',
		'BLOQUEO_TARJETA':'&#xe028;',
		'DESBLOQUEO':'&#xe030;',
		'ENTREGAR_A_TARJETAHABIENTE':'&#xe011;',
		'ENVIAR_A_EMPRESA':'&#xe05e;',
		'RECIBIR_EN_EMPRESA':'&#xe062;',
		'RECIBIR_EN_BANCO':'&#xe09c;'}
		$.each(data.result.detalleEmisiones, function (k, v) {

			//$.inArray('Enviado a Banco', data.result.operacioneTarjeta) !== -1 ? alert(1) : alert(2);

			tr = '<tr class="' + data.result.pagina+ '" tjta="' + v.nroTarjeta + '" id_ext_per="' + v.cedula + '"><td class="checkbox-select"><input id="check-oneTM" type="checkbox" value=""/></td>';
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.nroTarjeta + '</td>';
			tr += '<td class="bp-min-width">' + v.ordenS + '</td>';
			tr += '<td class="bp-min-width">' + v.nroLote + '</td>';
			tr += '<td class="bp-min-width">' + v.edoEmision + '</td>';
			tr += '<td class="bp-min-width">' + v.edoPlastico + '</td>';
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.nombre.toLowerCase().replace(/(^| )(\w)/g, function (x) {
				return x.toUpperCase();
			}) + '</td>';
			tr += '<td class="bp-min-width">' + v.cedula + '</td>';
			/* tr += '<td id="saldo' + v.noTarjetaConMascara.replace(/[*]/g, "") + '" class="bp-min-width">-</td>'; //saldo */
			 tr += '<td class="bp-min-width">';
			 $.each(data.result.operacioneTarjeta, function (i, j)
			{
				if(v.edoEmision == j.edoTarjeta)
				{
					for(k in j.operacion)
					{
						var	operacion = j.operacion[k].replace(/\s/g,'_');
						tr += '<a id="'+operacion+'" title="'+MaysPrimera(j.operacion[k].toLowerCase())+'" ><span class="icon" data-icon="'+iconos[operacion]+'"></span></a>';
					}
				}
			})
			tr += '</td></tr>';
			$('.table-text-service tbody').append(tr);
			aumenta++;
		});


		paginar();

	} else {
		$('#resultado-tarjetas').hide();
		notificacion("Consulta tarjetas transferencia maestra", "<h2>Empresa sin tarjetas asociadas</h2><h6>Saldo disponible: " + serv_var.saldoDispon + "</h6>") //$('.table-text-aut tbody').append('<h2>Sin resultados</h2>');
	}
}

// PAGINACIÓN PARA LA TABLA DE RESULTADOS
function paginar() {
	$('#paginado-TM').paginate({
		count: serv_var.pgTotal,
		display: serv_var.paginas,
		start: serv_var.pgActual,
		border: false,
		text_color: '#79B5E3',
		background_color: 'none',
		text_hover_color: '#2573AF',
		background_hover_color: 'none',
		images: false,
		onChange: function (page) {
			if (!$('.table-text-service').find($('.' + page)).hasClass(page)) {
				$('.table-text-service tbody tr').hide();
				if ($('#select-allR').is(':checked')) {
					$(':checkbox').each(function () {
						this.checked = 0;
					});
					serv_var.noTarjetas = "";
					serv_var.dni_tarjetas = "";
					serv_var.monto = [];
					serv_var.fallidas = 0;
				}
				$("#resultado-tarjetas").hide();
				buscar(page);
			}
			$('.table-text-service tbody tr').hide();
			$('.table-text-service .' + page).show();
			$('#paginado-TM .jPag-pages').css('width', '350px')
		}
	});
	$('#paginado-TM .jPag-pages').css('width', '350px')
}

// LIMPIAR LOS CHECK Y CAMPO CLAVE
function resett() {
	$(':checkbox').each(function () {
		this.checked = 0;
	});

	$.each($('.monto'), function () {
		$(this).hideBalloon();
	});

	serv_var.noTarjetas = "";
	serv_var.dni_tarjetas = "";
	serv_var.monto = [];
	serv_var.fallidas = 0;
}

function toFormatShow(valor) {
	valor = valor.toString();
	if (isoPais == 'Pe' || isoPais == 'Usd' || isoPais == 'Ec-bp') {
		return valor;
	}
	if (isoPais == 'Ve' || isoPais == 'Co') {
		valor = toFormat(valor);
		return (isoPais == 'Co' ? '$ ' : 'Bs. ') + formatoNumero(valor, 2, ",", ".");
	}
}

// DIALOGO DE NOTIFICACIONES
function notificacion(titulo, mensaje) {
	var canvas = "<div>" + mensaje + "</div>";

	$(canvas).dialog({

		dialogClass: "hide-close",
		title: titulo,
		modal: true,
		maxWidth: 700,
		maxHeight: 300,
		resizable: false,
		position: {
			my: "top"
		},
		close: function() {
			resett();
			$(this).dialog("close");
		},
		buttons: {
			"Aceptar": { text: 'Aceptar', class: 'novo-btn-primary-modal',
				click: function () {

				resett();
				$(this).dialog("close");
				 }
				}
		}
	});

}



$("#exportXLS_a").on('click', function () {

	var servicio = $('#servicio').val(),
		lote = $('#lote').val(),
		cedula = $('#cedula').val(),
		tarjeta = $('#tarjeta').val();

	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	$('form#formulario').empty();
	$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
	$('form#formulario').append('<input type="hidden" name="servicio" value="' + servicio + '">');
	$('form#formulario').append('<input type="hidden" name="cedula" value="' + cedula + '">');
	$('form#formulario').append('<input type="hidden" name="tarjeta" value="' + tarjeta + '">');
	$('form#formulario').append('<input type="hidden" name="lote" value="' + lote + '">');
	$('form#formulario').append('<input type="hidden" name="paginaActual" value="' + serv_var.pgActual + '" />');
	$('form#formulario').attr('action', baseURL + api + isoPais + "/servicios/consultaTarjetasExpXLS");
	$('form#formulario').submit();
});



// ACCION EVENTO ICON->RECIBIR EN BANCO
$(".table-text-service").on('click', '#RECIBIR_EN_BANCO', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea recibir la tarjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Recibir tarjeta en banco',url,op,mensaje)


});

// ACCION EVENTO ICON->RECIBIR EN EMRPESA
$(".table-text-service").on('click', '#RECIBIR_EN_EMPRESA', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea recibir la tarjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Recibir tarjeta en empresa',url,op,mensaje)


});

// ACCION EVENTO ICON->BLOQUEAR TARJETA
$(".table-text-service").on('click', '#BLOQUEO_TARJETA', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea bloquear la atrjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Bloqueo de tarjeta',url,op,mensaje)

})


// ACCION EVENTO ICON->ENVIAR A EMPRESA
$(".table-text-service").on('click', '#ENVIAR_A_EMPRESA', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea enviar la tarjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Enviar tarjeta a empresa',url,op,mensaje)

})

// ACCION EVENTO ICON->ENTREGAR_A_TARJETAHABIENTE
$(".table-text-service").on('click', '#ENTREGAR_A_TARJETAHABIENTE', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea entregar la tarjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Entregar tarjeta',url,op,mensaje)

})

// ACCION EVENTO ICON->DESBLOQUEO
$(".table-text-service").on('click', '#DESBLOQUEO', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var mensaje = 'Si desea desbloquear la tarjeta pulse aceptar de lo contrario cancelar.'
			op = '20'
			url = '1'

	procesar('Desbloquear tarjeta',url,op,mensaje)

})

// ACCION EVENTO ICON->ACTUALIZAR DATOS
$(".table-text-service").on('click', '#ACTUALIZAR_DATOS', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

	var canvas = "<div id='dialog-confirm'>";
	canvas += "<form name='no-form' onsubmit='return false'>";
	canvas += '<div id="campos-transfer">';
	canvas += '<span><p>Nombre:</p>';
	canvas += '<input  type="text" name="pass" id="nombres">';
	canvas += '<li id="errornombre" style="display:none"></li>';
	canvas += '</span>';
	canvas += '<span><p>Apellidos:</p>';
	canvas += '<input type="text" name="pass" id="apellidos">';
	canvas += '<li id="errorapellido" style="display:none"></li>';
	canvas += '</span>';
	canvas += '</div>';
	canvas += '<div id="campos-transfer">';
	canvas += '<span><p>Correo:</p>';
	canvas += '<input type="text" name="pass" id="correo" >';
	canvas += '<li id="errorcorreo" style="display:none"></li>';
	canvas += '</span>';
	canvas += '<span><p>PIN: </p>';
	canvas += '<input type="password" name="numero" id="pin" maxlength="4">';
	canvas += '<li id="errorpin" style="display:none"></li>';
	canvas += '</span>';
	canvas += '</div>';
	canvas += "</fieldset><h5 id='msg'></h5>";
	canvas += "</form>"
	canvas += "</div>"

	$(canvas).dialog({

		dialogClass: "close",
		title: 'Actualizar datos tarjetahabiente',
		modal: true,
		width: 500,
		top: 900,
		position: {
			my: "top 800"
		},
		close: function() {
			resett();
			$(this).dialog("destroy");
		},
		buttons: {
			Aceptar: function() {
				$(this).find('#msg').empty();
				validarFields();


			}
		}
	});



})

$('#pin').on('input', function () {
	this.value = this.value.replace(/[^0-9]/g,'');
});

function validarFields()
{
	var valNombre = $('#dialog-confirm').find('#nombres')
	var valApellidos = $('#dialog-confirm').find('#apellidos')
	var valCorreo = $('#dialog-confirm').find('#correo')
	var valPin = $('#dialog-confirm').find('#pin')
	var errorName = $('#dialog-confirm').find('#errornombre')
	var errorApellido = $('#dialog-confirm').find('#errorapellido')
	var errorCorreo = $('#dialog-confirm').find('#errorcorreo')
	var errorPin = $('#dialog-confirm').find('#errorpin')

	spaceString('#nombres')
	spaceString('#apellidos')
	spaceString('#correo')
	spaceString('#pin')

	var camposValid = ''
			msgValido = ''
			validInput = true
			descRegExp = /^['a-z0-9ñáéíóú ,.:()']+$/i
			emailRegExp = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
			numRegExp = /^\d+$/

	if(valNombre.val() === '')
	{
		errorName.show();
		errorName.html('El campo no puede estar vacio')
		validInput = false;
		valNombre.addClass('textbox-transfer');
	}
	else if(!descRegExp.test(valNombre.val()))
	{
		errorName.show();
		errorName.html('No se admiten caracteres especiales')
		validInput = false;
		valNombre.addClass('textbox-transfer');
	}
	else{
		errorName.hide();
		validInput = true;
		valNombre.removeClass('textbox-transfer');
	}

	if(valApellidos.val() === '')
	{
		errorApellido.show();
		errorApellido.html('El campo no puede estar vacio')
		validInput = false;
		valApellidos.addClass('textbox-transfer');
	}
	else if(!descRegExp.test(valApellidos.val()))
	{
		errorApellido.show();
		errorApellido.html('No se admiten caracteres especiales')
		validInput = false;
		valApellidos.addClass('textbox-transfer');
	}
	else{
		errorApellido.hide();
		validInput = true;
		valApellidos.removeClass('textbox-transfer');
	}

	if(valCorreo.val() === '')
	{
		errorCorreo.show();
		errorCorreo.html('El campo no puede estar vacio')
		validInput = false;
		valCorreo.addClass('textbox-transfer');
	}
	else if(!emailRegExp.test(valCorreo.val()))
	{
		errorCorreo.show();
		errorCorreo.html('Formato de correo incorrecto (ejemlo@datos.com)')
		validInput = false;
		valCorreo.addClass('textbox-transfer');
	}
	else{
		errorCorreo.hide();
		validInput = true;
		valCorreo.removeClass('textbox-transfer');
	}

	if(!numRegExp.test(valPin.val()))
	{
		errorPin.show();
		errorPin.html('El campo debe ser numerico')
		validInput = false;
		valPin.addClass('textbox-transfer');
	}
	else if(valPin.val().length != 4)
	{
		errorPin.show();
		errorPin.html('El campo debe contener 4 numeros')
		validInput = false;
		valPin.addClass('textbox-transfer');
	}
	else{
		errorPin.hide();
		validInput = true;
		valPin.removeClass('textbox-transfer');
	}

	$('#dialog-confirm').find('#msg').append(camposValid);
}

function spaceString(name)
{
	var Field = $('#dialog-confirm').find(name)

	var cambia = Field.val()
			cambia = cambia.trim()
			cambia =  cambia.replace(/ +/g, ' ')
			Field.val(cambia)
}

function MaysPrimera(string){
  return string.charAt(0).toUpperCase() + string.slice(1);
}

//PROCESAR OPERACION
function procesar(titulo, url, operacion, mensaje) {
	var canvas = "<div id='dialog-confirm'>";
	canvas += "<form name='no-form' onsubmit='return false'>";
	canvas += "<center>Tarjeta: " + serv_var.noTarjetas + "</center>";
	canvas += "<br><p>" + mensaje + "</p>";
	canvas += "</fieldset><h5 id='msg'></h5>";
	canvas += "</form>"
	canvas += "</div>"

	$(canvas).dialog({

		dialogClass: "hide-close",
		title: titulo,
		modal: true,
		position: {
			my: "top 800",
		},
		close: function() {
			resett();
			$(this).dialog("destroy");
		},
		buttons: {
			"Cancelar": { text: 'Cancelar', class: 'novo-btn-secondary-modal', style: 'border-color: #ffdd00 !important;background:white !important;',
			click: function () {
			$(this).dialog("close"); }
			},
			Aceptar: function() {
			}
		}
	});
}
