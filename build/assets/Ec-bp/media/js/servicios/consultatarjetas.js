var serv_var = {
	pgTotal: 1,
	pgActual: 1,
	busk: false,
	paginas: 10,
	paginar: true,
	dni_tarjetas: "",
	noTarjetas: "",
	TotalTjts: 0,
	cargo: 'show',
	abono: 'show',
	consulta: 'show',
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
		data_dni: $('#servicio').val(),
		data_tjta: '',
		data_pg: pgSgt,
		data_paginas: serv_var.paginas,
		data_paginar: serv_var.paginar
	})
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {
		format: CryptoJSAesJson
	}).toString();
	$.post(baseURL + api + isoPais + "/servicios/transferencia-maestra/buscar", {
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
				$.inArray('trasal', data.funciones) !== -1 ? $('#consultar-tjta').show() : serv_var.consulta = 'hidden';
				$.inArray('tracar', data.funciones) !== -1 ? $('#cargo-tjta').show() : serv_var.cargo = 'hidden';
				$.inArray('traabo', data.funciones) !== -1 ? $('#abonar-tjta').show() : serv_var.abono = 'hidden';

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
		$('.table-text-aut tbody').empty();
	}
	serv_var.maestroParam = data.result.maestroParametros;
	serv_var.saldoDispon = data.result.maestroDeposito.saldoDisponible;
	serv_var.cantXdia = data.result.cantXDia.lista;
	serv_var.acumXsem = data.result.acumXSemana.lista;

	$("#saldoEmpresa").text('Saldo disponible: ' + toFormatShow(serv_var.saldoDispon));
	$('#resultado-tarjetas').find('#saldoDisponible').text('Saldo disponible: ' + toFormatShow(serv_var.saldoDispon));
	$('#resultado-tarjetas').find('#comisionTrans').text('Comisión por transacción: ' + toFormatShow(serv_var.maestroParam.costoComisionTrans));
	$('#resultado-tarjetas').find('#comisionCons').text('Comisión por consulta saldo: ' + toFormatShow(serv_var.maestroParam.costoComisionCons));

	var tr;
	serv_var.pgTotal = parseInt(data.result.listaTarjetas[0].totalPaginas, 10);
	serv_var.pgActual = parseInt(data.result.listaTarjetas[0].paginaActual, 10);

	if (data.result.listadoTarjetas.lista.length > 0) {
		serv_var.TotalTjts += data.result.listadoTarjetas.lista.length;
		$('#textS').empty();
		$('#textS').append('<em>Seleccionar todo (' + serv_var.TotalTjts + ' de ' + data.result.listaTarjetas[0].totalRegistros + ')</em>');
		$('.table-text-service thead th').css('min-width', '75px');
		$('.table-text-service tbody td').css('min-width', '75px');

		$.each(data.result.listadoTarjetas.lista, function (k, v) {
			tr = '<tr class="' + data.result.listaTarjetas[0].paginaActual + '" tjta="' + v.noTarjetaConMascara + '" id_ext_per="' + v.id_ext_per + '"><td class="checkbox-select"><input id="check-oneTM" type="checkbox" value=""/></td>';
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.noTarjetaConMascara + '</td>';
			tr += '<td id="estatus' + v.noTarjetaConMascara.replace(/[*]/g, "") + '" class="bp-min-width">-</td>'; //estatus
			tr += '<td id="td-nombre-2" class="bp-min-width">' + v.NombreCliente.toLowerCase().replace(/(^| )(\w)/g, function (x) {
				return x.toUpperCase();
			}) + '</td>';
			tr += '<td class="bp-min-width">' + v.id_ext_per + '</td>';
			tr += '<td class="bp-min-width">' + v.id_ext_per + '</td>';
			tr += '<td class="bp-min-width">' + v.id_ext_per + '</td>';
			tr += '<td class="bp-min-width">' + v.id_ext_per + '</td>';
			tr += '<td id="saldo' + v.noTarjetaConMascara.replace(/[*]/g, "") + '" class="bp-min-width">-</td>'; //saldo
			tr += '<td class="bp-min-width"><a id="consulta_saldo" title="consulta saldo" ' + serv_var.consulta + '><span class="icon" data-icon="&#xe072;"></span></a>';
			tr += '<a id="abono_tarjeta" title="abono tarjeta" ' + serv_var.abono + '><span class="icon" data-icon="&#xe031;"></span></a>';
			tr += '<a id="cargo_tarjeta" title="cargo tarjeta" ' + serv_var.cargo + '><span class="icon" data-icon="&#xe08d;"></span></a>';
			tr += '</td></tr>';

			$('.table-text-service tbody').append(tr);
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

	$(':input').each(function () {
		$(this).val('');
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