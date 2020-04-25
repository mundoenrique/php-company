var serv_var = {
	pgTotal: 1,
	pgActual: 1,
	busk: false,
	paginas: 10,
	paginar: true,
	dni_tarjetas: [],
	noTarjetas: [],
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
	fallidas: 0,
	masivos:[],
	lote:[],
	estado_nuevo:'',
	estado_anterior:[],
	items:[]
}

$('#buscar').on('click', function () {

	$(':checkbox').each(function () {
		this.checked = 0;
	});
	serv_var.masivos = [];
	var RE = /^\d*$/,
		servicio = $('#servicio'),
		lote = $('#lote'),
		cedula = $('#cedula'),
		tarjeta = $('#tarjeta'),
		validInput = true,
		camposValid = '<div id="validar">';

	if (servicio.val().length == 0 && lote.val().length == 0 && cedula.val().length == 0 && tarjeta.val().length == 0) {
		camposValid += '<p>* Debe agregar al menos un campo</p>';
		validInput = false;
	} else {

		if (servicio.val().length > 0 && !RE.test(servicio.val())) {
			camposValid += '<p>* El campo orden de servicio debe ser numérico</p>';
			validInput = false;
			servicio.css('border-color', '#cd0a0a')
		} else {
			servicio.removeAttr('style');
		}

		if (lote.val().length > 0 && !RE.test(lote.val())) {
			camposValid += '<p>* El campo lote debe ser numérico</p>';
			validInput = false;
			lote.css('border-color', '#cd0a0a')
		} else {
			lote.removeAttr('style');
		}

		if (cedula.val().length > 0 && !RE.test(cedula.val())) {
			camposValid += '<p>* El campo cédula debe ser numérico</p>';
			validInput = false;
			cedula.css('border-color', '#cd0a0a')
		} else {
			cedula.removeAttr('style');
		}

		if (tarjeta.val().length > 0 && !RE.test(tarjeta.val())) {
			camposValid += '<p>* El campo tarjeta debe ser numérico</p>';
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
						resett()
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
				serv_var.lote.push($(this).parents('tr').attr('num_lote'));
				serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
				serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
				serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
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


		var iconos = {'ACTUALIZAR_DATOS':'&#xe02d;',
		'CONSULTA_SALDO_TARJETA':'&#xe022;',
		'BLOQUEO_TARJETA':'&#xe028;',
		'DESBLOQUEO':'&#xe03f;',
		'ENTREGAR_A_TARJETAHABIENTE':'&#xe011;',
		'ENVIAR_A_EMPRESA':'&#xe05e;',
		'RECIBIR_EN_EMPRESA':'&#xe062;',
		'RECIBIR_EN_BANCO':'&#xe09c;',
		'NINGUNA':'-'}

		var validaope = []

		for(var l in data.result.operacioneTarjeta)
		{
			validaope.push(data.result.operacioneTarjeta[l].edoTarjeta)
		}

		var opcmasivo = {'Recibir en banco': 'Recibido en Banco # 1',
		'Enviar a empresa': 'Enviado a Empresa # 1',
		'Recibir en empresa': 'Recibido en empresa # 1',
		'Entregar a tarjetahabiente':'Entregada a Tarjetahabiente / Activa # 1',
		'Bloqueo tarjeta' : 'bloqueo # 2',
		'Consulta saldo tarjeta': 'saldo # 2',
		'Desbloqueo': 'desbloqueo # 2'}

		$.each(data.result.detalleEmisiones, function (k, v) {

			var statusEmi = v.edoEmision.split('/')[0]
			//var statusEmi = v.edoEmision.slice(0, v.edoEmision.indexOf("/"));
			var valida = $.inArray(v.edoEmision, validaope) !== -1 ? 1:0;
			var personal = [];
			personal.push(v.nombres,v.apellidos,v.numCelular,v.email)
			tr = '<tr class="' + data.result.pagina+ '" tjta="' + v.nroTarjeta + '" num_lote="'+v.nroLote+'" edo_anterior="'+statusEmi[0]+'" id_ext_per="' + v.cedula + '" personal="' + personal + '" >';
			tr += '<td class="checkbox-select"><input id="check-oneTM" type="checkbox" value=""/></td>';
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.nroTarjeta + '</td>';
			tr += '<td class="bp-min-width">' + v.ordenS + '</td>';
			tr += '<td class="bp-min-width">' + v.nroLote + '</td>';
			tr += '<td class="bp-min-width">' + statusEmi + '</td>';
			tr += '<td id="statusPlastico' + v.nroTarjeta.replace(/[*]/g, "") + '"class="bp-min-width">' + v.edoPlastico + '</td>';
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.nombre.toLowerCase().replace(/(^| )(\w)/g, function (x) {
				return x.toUpperCase();
			}) + '</td>';
			tr += '<td class="bp-min-width">' + v.cedula + '</td>';
			 tr += '<td id="saldo' + v.nroTarjeta.replace(/[*]/g, "") + '" class="bp-min-width"> - '; //saldo
			 tr += '</td>'
			 tr += '<td id="opciones' + v.nroTarjeta.replace(/[*]/g, "") + '"class="bp-min-width">';
			 var operacion = ''
			 $.each(data.result.operacioneTarjeta, function (i, j)
			{
				if(v.edoEmision == j.edoTarjeta && valida == 1)
				{
					for(k in j.operacion)
					{
						nomoperacion = MaysPrimera(j.operacion[k].toLowerCase());
						operacion = j.operacion[k].replace(/\s/g,'_');
						tr += '<a id="'+operacion+'" title="'+nomoperacion+'" ><span class="icon" data-icon="'+iconos[operacion]+'"></span></a>';
						$.inArray(nomoperacion, serv_var.masivos) !== -1 ?  '' : serv_var.masivos.push(nomoperacion);
					}
				}
			})
			if(operacion == '')
			{
				tr += '-';
			}
			tr += '</td></tr>';
			$('.table-text-service tbody').append(tr);

		});

		optionsmasivo = '';
		for (var propiedad in opcmasivo) {
			if (opcmasivo.hasOwnProperty(propiedad)) {
				if($.inArray(propiedad, serv_var.masivos) !== -1)
				{
					optionsmasivo += '<option value="'+opcmasivo[propiedad]+'">'+propiedad+'</option>'
				}
			}
		}

		if(serv_var.masivos.length !=0)
		{
			$('#select-tipo-proceso').empty();
			$('#select-tipo-proceso').append('<option value="">Seleccionar</option>');
			$('#process-masivo').show()
			$('#select-tipo-proceso').append(optionsmasivo);
		}
		else
		{
			$('#process-masivo').hide()
		}

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
					serv_var.noTarjetas = [];
					serv_var.dni_tarjetas = [];
					serv_var.lote = [];
					serv_var.estado_anterior = [];
					serv_var.monto = [];
					serv_var.fallidas = 0;
				}
				$("#resultado-tarjetas").hide();
				buscar(page);
			}
			$('.table-text-service tbody tr').hide();
			$('.table-text-service .' + page).show();
			$('#paginado-TM .jPag-pages').css('width', '600px')
		}
	});
	$('#paginado-TM .jPag-pages').css('width', '600px')
}

// LIMPIAR LOS CHECK Y CAMPO CLAVE
function resett() {
	$(':checkbox').each(function () {
		this.checked = 0;
	});

	$.each($('.monto'), function () {
		$(this).hideBalloon();
	});

	serv_var.noTarjetas = [];
	serv_var.dni_tarjetas = [];
	serv_var.lote = [];
	serv_var.estado_anterior = [];
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
function notificacion(titulo, mensaje, opcion) {

	opcion = opcion == undefined ? 0 : opcion;
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
		buttons: {
			"Aceptar": { text: 'Aceptar', class: 'novo-btn-primary-modal',
				click: function () {
					switch(opcion){
						case 0:
							resett();
							$(this).dialog("close");
							break
						case 1:
							resett();
							$(this).dialog("close");
							serv_var.TotalTjts = 0;
							serv_var.masivos = [];
							$('#resultado-tarjetas').hide();
							$('.table-text-service tbody').empty();
							buscar(serv_var.pgActual)
							break
						case 2:
							$(this).dialog("close");
							break
					}
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

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Recibido en Banco';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));

	alerta = 'la acción de recibir en banco,'
	op = "Recibido en Banco"
	url = "/servicios/cambiarEstadoemision"
	procesar('Recibir en banco',url,op,alerta)
});

// ACCION EVENTO ICON->CONSULTA DE SALDO
$(".table-text-service").on('click', '#CONSULTA_SALDO_TARJETA', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Saldo';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));

	alerta = 'la consulta de saldo,'
	op = 'saldo'
	url = '/servicios/cambiarEstadotarjeta'
	procesar('Consultar saldo',url,op,alerta)
});

// ACCION EVENTO ICON->RECIBIR EN EMRPESA
$(".table-text-service").on('click', '#RECIBIR_EN_EMPRESA', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Recibido en Empresa';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));

	alerta = 'la acción de recibir en empresa,'
	op = "Recibido en empresa"
	url = "/servicios/cambiarEstadoemision";
	procesar('Recibir tarjeta en empresa',url,op,alerta)

});

// ACCION EVENTO ICON->BLOQUEAR TARJETA
$(".table-text-service").on('click', '#BLOQUEO_TARJETA', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Bloqueada';
	serv_var.estado_anterior = ['Desbloqueada'];
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));
	op = 'bloqueo'
	url = '/servicios/cambiarEstadotarjeta'

	procesar('Bloquear de tarjeta',url,op)

})

// ACCION EVENTO ICON->DESBLOQUEO
$(".table-text-service").on('click', '#DESBLOQUEO', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Desbloqueada';
	serv_var.estado_anterior = ['Bloqueada'];
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));
	op = 'desbloqueo'
	url = '/servicios/cambiarEstadotarjeta'

	procesar('Desbloquear de tarjeta',url,op)

})


// ACCION EVENTO ICON->ENVIAR A EMPRESA
$(".table-text-service").on('click', '#ENVIAR_A_EMPRESA', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Enviado a Empresa';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));

	alerta = 'el envío a empresa,'
	op = "Enviado a Empresa"
	url = "/servicios/cambiarEstadoemision"
	procesar('Enviar tarjeta a empresa',url,op,alerta)

})

// ACCION EVENTO ICON->ENTREGAR_A_TARJETAHABIENTE
$(".table-text-service").on('click', '#ENTREGAR_A_TARJETAHABIENTE', function() {

	resett();
	serv_var.noTarjetas.push($(this).parents('tr').attr('tjta'));
	serv_var.dni_tarjetas.push($(this).parents('tr').attr('id_ext_per'));
	serv_var.estado_nuevo = 'Entregada a Tarjetahabiente / Activa';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));

	alerta = 'la entrega a tarjetahabiente,'
	op = "Entregada a Tarjetahabiente / Activa"
	url = "/servicios/cambiarEstadoemision"
	procesar('Entregar tarjeta',url,op,alerta)

})

var nombres, apellidos, correo, celular, clave;

// ACCION EVENTO ICON->ACTUALIZAR DATOS
$(".table-text-service").on('click', '#ACTUALIZAR_DATOS', function() {

	serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
	serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];
	serv_var.estado_nuevo = 'Actualizar datos';
	serv_var.estado_anterior.push($(this).parents('tr').attr('edo_anterior'));
	serv_var.lote.push($(this).parents('tr').attr('num_lote'));



	var dataPersona = $(this).parents('tr').attr('personal');
	dataPersona = dataPersona.split(',');
	var canvas = "<div id='dialog-confirm'>";
	canvas += "<form name='no-form' onsubmit='return false'>";
	canvas += '<div id="campos-transfer">';
	canvas += '<span><p>Nombre:</p>';
	canvas += '<input type="text" name="pass" id="nombres" value="'+dataPersona[0]+'">';
	canvas += '<li id="errornombre" style="display:none"></li>';
	canvas += '</span>';
	canvas += '<span><p>Apellidos:</p>';
	canvas += '<input type="text" name="pass" id="apellidos" value="'+dataPersona[1]+'">';
	canvas += '<li id="errorapellido" style="display:none"></li>';
	canvas += '</span>';
	canvas += '</div>';
	canvas += '<div id="campos-transfer">';
	canvas += '<span><p>Correo:</p>';
	canvas += '<input type="text" name="pass" id="correo" value="'+dataPersona[3]+'">';
	canvas += '<li id="errorcorreo" style="display:none"></li>';
	canvas += '</span>';
	// canvas += '<span><p>PIN: </p>';
	// canvas += '<input type="password" name="numero" id="pin" maxlength="4">';
	// canvas += '<li id="errorpin" style="display:none"></li>';
	// canvas += '</span>';
	canvas += '</div>';
	canvas += '<div id="campos-transfer">';
	canvas += '<span><p>Teléfono celular:</p>';
	canvas += '<input type="text" name="pass" id="celular" value="'+dataPersona[2]+'">';
	canvas += '<li id="errorcelular" style="display:none"></li>';
	canvas += '</span>';
	canvas += '<span><p>Contraseña: </p>';
	canvas += '<input type="password" name="numero" id="clave" placeholder="Ingrese su contraseña" maxlength="16">';
	canvas += '<li id="errorclave" style="display:none"></li>';
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
				var validate = validarFields();
				if(validate == true)
				{
					nombres = $('#nombres').val();
					apellidos = $('#apellidos').val();
					correo =  $('#correo').val();
					celular = $('#celular').val();
					clave = $('#clave').val();
					$(this).dialog("destroy");
					url = "/servicios/cambiarEstadoemision";
					llamarActDatos(url,'Actualizar datos');
				}
			}
		}
	});



})

function validarFields()
{
	var valNombre = $('#dialog-confirm').find('#nombres')
	var valApellidos = $('#dialog-confirm').find('#apellidos')
	var valCorreo = $('#dialog-confirm').find('#correo')
	var valCelular = $('#dialog-confirm').find('#celular')
	var valClave = $('#dialog-confirm').find('#clave')
	var errorName = $('#dialog-confirm').find('#errornombre')
	var errorApellido = $('#dialog-confirm').find('#errorapellido')
	var errorCorreo = $('#dialog-confirm').find('#errorcorreo')
	var errorCelular = $('#dialog-confirm').find('#errorcelular')
	var errorClave = $('#dialog-confirm').find('#errorclave')

	spaceString('#nombres')
	spaceString('#apellidos')
	spaceString('#correo')
	spaceString('#celular')
	spaceString('#clave')

	var camposValid = ''
			msgValido = '';
			validInput = true;
			nomRegExp = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/i;
			descRegExp = /^['a-z0-9ñáéíóú ,.:()']+$/i;
			emailRegExp = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
			numRegExp = /\d+/;

	if(valNombre.val() === '')
	{
		errorName.show();
		errorName.html('El campo no puede estar vacío')
		validInput = false;
		valNombre.addClass('textbox-transfer');
	}
	else if(numRegExp.test(valNombre.val()))
	{
		errorName.show();
		errorName.html('No se admiten números')
		validInput = false;
		valNombre.addClass('textbox-transfer');
	}
	else if(!nomRegExp.test(valNombre.val()))
	{
		errorName.show();
		errorName.html('No se admiten caracteres especiales')
		validInput = false;
		valNombre.addClass('textbox-transfer');
	}
	else{
		errorName.hide();
		valNombre.removeClass('textbox-transfer');
	}

	if(valApellidos.val() === '')
	{
		errorApellido.show();
		errorApellido.html('El campo no puede estar vacío')
		validInput = false;
		valApellidos.addClass('textbox-transfer');
	}
	else if(numRegExp.test(valApellidos.val()))
	{
		errorApellido.show();
		errorApellido.html('No se admiten números')
		validInput = false;
		valApellidos.addClass('textbox-transfer');
	}
	else if(!nomRegExp.test(valApellidos.val()))
	{
		errorApellido.show();
		errorApellido.html('No se admiten caracteres especiales')
		validInput = false;
		valApellidos.addClass('textbox-transfer');
	}
	else{
		errorApellido.hide();
		valApellidos.removeClass('textbox-transfer');
	}

	if(valCorreo.val() === '')
	{
		errorCorreo.show();
		errorCorreo.html('El campo no puede estar vacío')
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
		valCorreo.removeClass('textbox-transfer');
	}

	if(valCelular.val().length != 10)
	{
		errorCelular.show();
		errorCelular.html('El campo debe tener 10 dígitos')
		validInput = false;
		valCelular.addClass('textbox-transfer');

	}else if(!numRegExp.test(valCelular.val())){
		errorCelular.show();
		errorCelular.html('El campo debe ser numérico')
		validInput = false;
		valCelular.addClass('textbox-transfer');

	}else if(!/^[0-9][1-9]+/.test(valCelular.val())){
		errorCelular.show();
		errorCelular.html('El campo solo puede tener un solo cero al inicio')
		validInput = false;
		valCelular.addClass('textbox-transfer');

	}else{
		errorCelular.hide();
		valCelular.removeClass('textbox-transfer');
	}

	if(valClave.val() === '')
	{
		errorClave.show();
		errorClave.html('El campo no puede estar vacío')
		validInput = false;
		valClave.addClass('textbox-transfer');
	}
	else{
		errorClave.hide();
		valClave.removeClass('textbox-transfer');
	}

	return validInput;
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
function procesar(titulo, url, op, alerta) {
	op = op != undefined ? op : 1;
	var canvas = "<div id='dialog-confirm'>";
	canvas += "<form name='no-form' onsubmit='return false'>";
	canvas += "<center>Tarjeta: " + serv_var.noTarjetas + "</center>";
	canvas += "<br><p><input type='password' name='pass' id='pass' placeholder='Ingresa tu contraseña' size='28'></p>";
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

				pass = $(this).find('#pass').val();

				if (pass !== '') {
					var form= $(this).find('form');
					validateForms(form);
					if (form.valid()) {
						llamarWSCambio(pass, titulo,url, op, alerta);
						$(this).find('#pass').val('');
						$(this).dialog("destroy");
					} else {
						$(this).find('#msg').empty();
						$(this).find('#msg').append("Contraseña inválida");
					}
				} else {
					$(this).find('#msg').empty();
					$(this).find('#msg').append("Debes ingresar la contraseña");
				}
				resett();
			}
		}
	});
}

function llamarActDatos(url, title){
	var $aux = $('#loading').dialog({

		dialogClass: "hide-close",
		title: title,
		modal: true,
		bgiframe: true,
		dialogClass: 'hide-close',
		close: function() {
			$aux.dialog('close');
		},
		position: {
			my: "top"
		}
	});
	pass = hex_md5(clave);
	var op = "act_datos";
	var dataRequest = JSON.stringify ({
		lote : serv_var.lote,
		nombres: nombres,
		apellidos: apellidos,
		correo: correo,
		celular:celular,
		pass: pass,
		estado_nuevo: serv_var.estado_nuevo,
    estado_anterior: serv_var.estado_anterior,
    tarjeta: serv_var.noTarjetas,
    id_ext_per: serv_var.dni_tarjetas,
    opcion: op
		})

	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
	$.post(baseURL + api + isoPais + url,
	{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)} )
	.done(function(response){
		data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
		$aux.dialog("destroy");
		if (!data.result.ERROR) {
			notificacion('Actualizar datos','Proceso realizado correctamente', 1);

		}
		else{
			if (data.result.ERROR == '-29') {
				alert('Usuario actualmente desconectado');
				location.reload();
			} else {
				notificacion("mensaje", data.result.ERROR,2);
			}
		}
	})

}


function llamarWSCambio(pass,mensaje,url,op,alerta) {

	var $aux = $('#loading').dialog({

		dialogClass: "hide-close",
		title: mensaje,
		modal: true,
		bgiframe: true,
		dialogClass: 'hide-close',
		close: function() {
			$aux.dialog('close');
		},
		position: {
			my: "top"
		}
	});

	pass = hex_md5(pass);
	$('#claveMasivo').val("");

	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);


	var dataRequest = JSON.stringify ({
		lote : serv_var.lote,
		estado_nuevo: serv_var.estado_nuevo,
		estado_anterior: serv_var.estado_anterior,
		tarjeta: serv_var.noTarjetas,
		id_ext_per: serv_var.dni_tarjetas,
		opcion: op,
		pass: pass
		})

		dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
		$.post(baseURL + api + isoPais + url,
		{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)} )
		.done(function(response){
			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))

		if(data.result.bean){
			if(op == 'saldo' || op == 'bloqueo' || op == 'desbloqueo'){
				var contador_mas = 0;
				var contador_menos = 0;
				$.each(JSON.parse(data.result.bean), function(k, item) {
					if (item.rcNovoTrans=="0"){
						contador_mas = contador_mas + 1;
					}
					if (item.rcNovoTrans!="0"){
						contador_menos = contador_menos + 1;
					}
			});
			}
		}

			$aux.dialog("destroy");
			if(data.result.rc == -1){
				notificacion(mensaje, 'La contraseña es incorrecta. Por favor verifícala e intenta de nuevo.', reload);
			}else if(data.result.rc == -416){
				notificacion(mensaje,data.result.msg, reload);
			}else if(data.result.rc == -450){
				notificacion(mensaje,data.result.msg, reload);
			} else if(data.result.rc == -451){
				notificacion(mensaje,data.result.msg, reload);
			} else if(data.result.rc == -452){
				notificacion(mensaje,data.result.msg, reload);
			}else if(data.result.rc == -177){
				notificacion(mensaje,data.result.msg, reload);
			}else if (!data.result.ERROR) {
				var reload = 1;
				if(op == 'saldo')
				{
					reload = 2;
					mostrar_saldo(data)
				}
				switch(op){
					case 'saldo':
					case 'Recibido en Banco':
					case 'Enviado a Empresa':
					case 'Recibido en empresa':
					case 'Entregada a Tarjetahabiente / Activa':
					nota = 'Proceso realizado correctamente';
						break;
					case 'bloqueo':
					nota = 'Se han procesado exitosamente '+contador_mas+' Bloqueo(s) de tarjetas y no se lograron procesar '+contador_menos;
						break;
					case 'desbloqueo':
					nota = 'Se han procesado exitosamente '+contador_mas+' Desbloqueo(s) de tarjetas y no se lograron procesar '+contador_menos;
						break;
					default:
					nota = 'No se pudo realizar '+alerta+' intenta más tarde.';
					}
				notificacion(mensaje, nota, reload);
			}
			else{
				if (data.result.ERROR == '-29') {
					alert('Usuario actualmente desconectado');
					location.reload();
				} else {
					notificacion(mensaje, data.result.ERROR,2);
				}
			}
			resett()
})

}

// ACCIÓN EVENTO CHECK UNITARIO
$('.table-text-service').on('click', '#check-oneTM', function() {
	var dlote = $(this).parents('tr').attr('num_lote');
	var tjts = $(this).parents('tr').attr('tjta');
	var dnis = $(this).parents('tr').attr('id_ext_per');
	var edoant = ($(this).parents('tr').attr('edo_anterior'));

	if ($(this).is(':checked')) {
		serv_var.lote.push(dlote);
		serv_var.noTarjetas.push(tjts);
		serv_var.dni_tarjetas.push(dnis);
		serv_var.estado_anterior.push(edoant);
	}
	else{
		removeItemFromArr(serv_var.lote,dlote);
		removeItemFromArr(serv_var.noTarjetas,tjts);
		removeItemFromArr(serv_var.dni_tarjetas,dnis);
		removeItemFromArr(serv_var.estado_anterior,edoant);
	}

});

$("#select-tipo-proceso").on("change", function () {
	acrif = $('option:selected', this).attr("value");

	if(acrif !== '')
	{
		$('#claveMasivo, #button-masivo').prop('disabled', false);

	}
	else
	{
		$('#claveMasivo, #button-masivo').prop('disabled', true);

	}


});

//ACCION PARA LANZAR EL PROCESO MASIVO
$('#button-masivo').click(function() {

	serv_var.estado_nuevo = $("#select-tipo-proceso").val();

	urlproc = serv_var.estado_nuevo.split(' # ');
	serv_var.estado_nuevo = urlproc[0]
	var clamasivo = $('#claveMasivo')
	var errmasivo=''
	var alerta=''

	switch(serv_var.estado_nuevo){

		case 'Recibido en Banco':
			alerta = 'la acción de recibir en banco,';
			break;
		case 'Enviado a Empresa':
			alerta = 'el envío a empresa,'
			break;
		case 'Recibido en empresa':
			alerta = 'la acción de recibir en empresa,'
			break;
		case 'Entregada a Tarjetahabiente / Activa':
			alerta = 'la entrega a tarjetahabiente,'
			break;
		case 'saldo':
			alerta = 'la consulta de saldo,';
			break;
		case 'bloqueo':
			alerta = 'bloqueo de tarjeta,';
			break;
		case 'desbloqueo':
			alerta = 'desbloqueo de tarjeta,';
			break;
		}

	url = (urlproc[1] == 1) ? '/servicios/cambiarEstadoemision' : '/servicios/cambiarEstadotarjeta';

	if(serv_var.noTarjetas.length == 0)
	{
		errmasivo += '<p>* Debes seleccionar al menos un registro.</p>'
	}

	if(clamasivo.val() == '')
	{
		errmasivo += '<p>* Debes ingresar tu clave.</p>'
	}

	if(errmasivo != '')
	{
		notificacion('Proceso masivo',errmasivo, 2)
	}
	else
	{
		llamarWSCambio(clamasivo.val(), 'Proceso Masivo',url,urlproc[0],alerta);
	}
});

// MOSTRAR EL SALDO DISPONIBLE PARA CADA TARJETA LUEGO DE CONSULTAR
function mostrar_saldo(data) {
	$.each(JSON.parse(data.result.bean), function(k, t) {

		if(t.msgNovoTrans=="Approved or completed successfully"){
			$('#saldo' + t.numeroTarjeta.replace(/[*]/g, "")).text((t.saldo));
		}
		if(t.msgNovoTrans=="No apta para procesarse"){
			$('#statusPlastico' + t.numeroTarjeta.replace(/[*]/g, "")).text("Bloqueada");
			$('#saldo' + t.numeroTarjeta.replace(/[*]/g, "")).empty();
			$('#saldo' + t.numeroTarjeta.replace(/[*]/g, "")).append('<span title="'+t.msgNovoTrans+'"  class="icon" data-icon="&#xe04b;"></span>');
			$('#opciones' + t.numeroTarjeta.replace(/[*]/g, "")).empty();
			$('#opciones' + t.numeroTarjeta.replace(/[*]/g, "")).append('<a id="DESBLOQUEO" title="Desbloqueo"><span class="icon" data-icon="&#xe03f;"></span></a>');
		}
		if(t.msgNovoTrans=="Tarjeta Inactiva"){
			$('#statusPlastico' + t.numeroTarjeta.replace(/[*]/g, "")).text("Inactiva");
			$('#saldo' + t.numeroTarjeta.replace(/[*]/g, "")).empty();
			$('#saldo' + t.numeroTarjeta.replace(/[*]/g, "")).append('<span title="'+t.msgNovoTrans+'"  class="icon" data-icon="&#xe04b;"></span>');
			$('#opciones' + t.numeroTarjeta.replace(/[*]/g, "")).empty();
			$('#opciones' + t.numeroTarjeta.replace(/[*]/g, "")).text("-");
		}
	});
}

//FUNCION PARA REMOVER UN ELMENTO DE UN ARRAY
function removeItemFromArr ( arr, item ) {
	var i = arr.indexOf( item );
	arr.splice( i, 1 );
}
