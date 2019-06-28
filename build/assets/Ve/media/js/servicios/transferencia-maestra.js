$(function () {


	$('#filtroOS').show();
	$("#dni").attr("maxlength", "12");


	// VARIABLES GLOBALES PARA URL

	var valido = true;

	// VARIABLES GLOBALES

	serv_var = {
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


	// ACCION DEL EVENTO PARA BUSCAR TARJETAS TM

	$('#buscar').on('click', function () {
		var errElem = $(this).siblings('#mensajeError');
		var form = $('#form-criterio-busqueda');
		errElem.fadeOut('fast');
		validateForms(form);
		if (form.valid()) {
			serv_var.busk = true;
			serv_var.TotalTjts = 0;
			buscar(1);
		} else {
			$('.div_tabla_detalle').fadeOut('fast');
			errElem.html('Debe ingresar datos numéricos');
			errElem.fadeIn('fast');
		}
	});


	// BUSCAR TARJETAS PARA TRANSFERENCIA MAESTRA

	function buscar(pgSgt) {

		var $aux = $('#loading').dialog({
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
			data_dni: $('#dni').val(),
			data_tjta: $('#nroTjta').val(),
			data_pg: pgSgt,
			data_paginas: serv_var.paginas,
			data_paginar: serv_var.paginar
		})
		dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
		$.post(baseURL + api + isoPais + '/servicios/transferencia-maestra/buscar', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
			.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
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

			$.each(data.result.listadoTarjetas.lista, function (k, v) {
				tr = '<tr class="' + data.result.listaTarjetas[0].paginaActual + '" tjta="' + v.noTarjetaConMascara + '" id_ext_per="' + v.id_ext_per + '"><td class="checkbox-select"><input id="check-oneTM" type="checkbox" value=""/></td>';
				tr += '<td id="td-nombre-2">' + v.noTarjetaConMascara + '</td>';
				tr += '<td id="estatus' + v.noTarjetaConMascara.replace(/[*]/g, "") + '">-</td>'; //estatus
				tr += '<td id="td-nombre-2">' + v.NombreCliente.toLowerCase().replace(/(^| )(\w)/g, function (x) {
					return x.toUpperCase();
				}) + '</td>';
				tr += '<td>' + v.id_ext_per + '</td>';
				tr += '<td id="saldo' + v.noTarjetaConMascara.replace(/[*]/g, "") + '">-</td>'; //saldo
				tr += '<td><input class="monto" style="width: 68%;"></td>'; //monto  onPaste="return false"
				tr += '<td><a id="consulta_saldo" title="consulta saldo" ' + serv_var.consulta + '><span class="icon" data-icon="&#xe072;"></span></a>';
				tr += '<a id="abono_tarjeta" title="abono tarjeta" ' + serv_var.abono + '><span class="icon" data-icon="&#xe031;"></span></a>';
				tr += '<a id="cargo_tarjeta" title="cargo tarjeta" ' + serv_var.cargo + '><span class="icon" data-icon="&#xe08d;"></span></a>';
				tr += '</td></tr>';

				$('.table-text-aut tbody').append(tr);
			});

			paginar();

		} else {
			$('#resultado-tarjetas').hide();
			notificacion("Consulta tarjetas transferencia maestro", "<h2>Empresa sin tarjetas asociadas</h2><h6>Saldo disponible: " + serv_var.saldoDispon + "</h6>") //$('.table-text-aut tbody').append('<h2>Sin resultados</h2>');
		}

	}


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


	// ACCIÓN EVENTO CHECK UNITARIO

	$('.table-text-aut').on('click', '#check-oneTM', function () {

		var tjts = $(this).parents('tr').attr('tjta');
		var dnis = $(this).parents('tr').attr('id_ext_per');

		if ($(this).is(':checked')) {
			serv_var.noTarjetas += tjts + ",";
			serv_var.dni_tarjetas += dnis + ",";
		} else {
			serv_var.noTarjetas = serv_var.noTarjetas.replace(tjts + ",", "");
			serv_var.dni_tarjetas = serv_var.dni_tarjetas.replace(dnis + ",", "");
			$(this).parents('tr').find('.monto').val('');
		}

	});


	// ACCION EVENTO ICON->CONSULTAR SALDO

	$(".table-text-aut").on('click', '#consulta_saldo', function () {

		serv_var.noTarjetas = [$(this).parents('tr').attr('tjta')];
		serv_var.dni_tarjetas = [$(this).parents('tr').attr('id_ext_per')];

		var op = '30';
		verif = calcularConsulta();

		if (verif && $('#clave').val() !== '') {
			llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/consultar', op, 'Consultando...');
		} else if (verif) {
			confirmar('Consultar saldo de tarjeta', baseURL + api + isoPais + '/servicios/transferencia-maestra/consultar', op, 'Consultando...');
		}

	});

	// ACCION EVENTO ICON->ABONAR TARJETA

	$(".table-text-aut").on('click', '#abono_tarjeta', function () {

		var op = '20';

		resettOp($(this).parents('tr').attr('tjta'));

		verif = calcularTrans(op);

		if (verif && $('#clave').val() !== '') {
			llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/abonar', op, 'Abonando...');
		} else if (verif) {
			confirmar('Confirmar abono a tarjeta', baseURL + api + isoPais + '/servicios/transferencia-maestra/abonar', op, 'Abonando...');
		}


	});

	// ACCION EVENTO ICON->CARGAR TARJETA

	$(".table-text-aut").on('click', '#cargo_tarjeta', function () {

		var op = '40';
		resettOp($(this).parents('tr').attr('tjta'));
		verif = calcularTrans(op);

		if (verif && $('#clave').val() !== '') {
			llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/cargar', op, 'Cargando...');
		} else if (verif) {
			confirmar('Confirmar cargo a tarjeta', baseURL + api + isoPais + '/servicios/transferencia-maestra/cargar', op, 'Cargando...');
		}

	});


	//ACCION EVENTO BOTON->CONSULTAR SALDO

	$('#consultar-tjta').on('click', function () {

		if (!(serv_var.noTarjetas instanceof Array)) {
			serv_var.noTarjetas = serv_var.noTarjetas.substr(0, serv_var.noTarjetas.lastIndexOf(','));
			serv_var.noTarjetas = serv_var.noTarjetas.split(',');
			serv_var.dni_tarjetas = serv_var.dni_tarjetas.substr(0, serv_var.dni_tarjetas.lastIndexOf(','));
			serv_var.dni_tarjetas = serv_var.dni_tarjetas.split(',');
		}

		if ($('#clave').val() != '' && serv_var.noTarjetas != "") {

			if (calcularConsulta()) {
				llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/consultar', '30', 'Consultando...');
			}

		} else {
			notificacion('Consulta a tarjeta', '<h2>Verifique que: </h2><h3>1. Ha seleccionado al menos una tarjeta</h3><h3>2. Ha ingresado su contraseña</h3>');

		}

	});

	//ACCION EVENTO BOTON->ABONAR A TARJETA

	$('#abonar-tjta').on('click', function () {

		if ($('#clave').val() != '' && calcularTrans('20')) {

			llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/abonar', '20', 'Abonando...');

		} else if ($('#clave').val() == '') {
			notificacion('Abono a tarjeta', '<h2>Verifique que: </h2><h3>1. Ha ingresado el monto a abonar</h3><h3>2. Ha ingresado su contraseña</h3>');

		}

	});

	//ACCION EVENTO BOTON->CARGAR A TARJETA

	$('#cargo-tjta').on('click', function () {


		if ($('#clave').val() != '' && calcularTrans('40')) {

			llamarWS($('#clave').val(), baseURL + api + isoPais + '/servicios/transferencia-maestra/cargar', '40', 'Cargando...');

		} else if ($('#clave').val() == '') {
			notificacion('Cargo a tarjeta', '<h2>Verifique que: </h2><h3>1. Ha ingresado el monto a cargar</h3><h3>2. Ha ingresado su contraseña</h3>');

		}

	});


	//PETICION A WS PARA LAS OPERACIONES (SALDO, ABONO, CARGO)

	function llamarWS(pass, url, operacion, mensaje) {

		var $aux = $('#loading').dialog({
			title: mensaje,
			modal: true,
			bgiframe: true,
			dialogClass: 'hide-close',
			close: function () {
				$aux.dialog('close');
			},
			position: {
				my: "top"
			}
		});

		pass = hex_md5(pass);
		$('#clave').val("");
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$.post(url, {
			'data-tarjeta': serv_var.noTarjetas,
			'data-id_ext_per': serv_var.dni_tarjetas,
			'data-pass': pass,
			'data-monto': serv_var.monto,
			'data-pg': 1,
			'data-paginas': 1,
			'data-paginar': false,
			ceo_name: ceo_cook
		})
			.done(function (data) {

				$aux.dialog("destroy");

				if (!data.ERROR) {
					serv_var.cantXdia = data.cantXDia.lista;
					serv_var.saldoDispon = data.maestroDeposito.saldoDisponible;
					serv_var.maestroParam = data.maestroParametros;
					serv_var.acumXsem = data.acumXSemana.lista;

					$('#resultado-tarjetas').find('#saldoDisponible').text('saldo disponible: ' + (serv_var.saldoDispon));
					$('#resultado-tarjetas').find('#comisionTrans').text('Comisión por transacción: ' + toFormatShow(serv_var.maestroParam.costoComisionTrans));
					$('#resultado-tarjetas').find('#comisionCons').text('Comisión por consulta saldo: ' + toFormatShow(serv_var.maestroParam.costoComisionCons));

					if (operacion == 30) {
						mostrar_saldo(data);
					}

					mostrar_estatus(data);
					notificacion(mensaje, '<h4>Proceso exitoso</h4><h5>' + serv_var.fallidas + ' tarjetas fallidas</h5><h5>Verifique estatus y/o saldo de sus tarjetas</h5>');

				} else {
					if (data.ERROR == '-29') {
						alert('Usuario actualmente desconectado');
						location.reload();
					} else {
						notificacion(mensaje, data.ERROR);
					}
				}
				resett();

			});

	}


	// CONFIRMAR OPERACION

	function confirmar(titulo, url, operacion, mensaje) {
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var canvas = "<div id='dialog-confirm'>";
		canvas += "<form name='no-form' onsubmit='return false'>";
		canvas += "<input type='hidden' name='ceo_name' value=" + ceo_cook + "/>";
		canvas += "<p>Tarjeta: " + serv_var.noTarjetas + "</p>";
		canvas += "<fieldset><input type='password' name='pass' id='pass' placeholder='Ingrese su contraseña' size='28'>";
		canvas += "</fieldset><h5 id='msg'></h5>";
		canvas += "</form>"
		canvas += "</div>"

		$(canvas).dialog({
			title: titulo,
			modal: true,
			position: {
				my: "center top",
				at: "center 500"
			},
			close: function () {
				resett();
				$(this).dialog("destroy");
			},
			buttons: {
				Aceptar: function () {
					pass = $(this).find('#pass').val();

					if (pass !== '') {
						llamarWS(pass, url, operacion, mensaje);
						$(this).find('#pass').val('');
						$(this).dialog("destroy");
					} else {
						$(this).find('#msg').empty();
						$(this).find('#msg').append("Debe ingresar la contraseña");
					}

					resett();
				}
			}
		});

	}


	// DIALOGO DE NOTIFICACIONES
	function notificacion(titulo, mensaje) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			resizable: false,
			position: {
				my: "top"
			},
			close: function () {
				resett();
				$(this).dialog("close");
			},
			buttons: {
				OK: function () {
					resett();
					$(this).dialog("close");
				}
			}
		});
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
				if (!$('.table-text-aut').find($('.' + page)).hasClass(page)) {
					$('.table-text-aut tbody tr').hide();
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
				$('.table-text-aut tbody tr').hide();
				$('.table-text-aut .' + page).show();
				$('#paginado-TM .jPag-pages').css('width', '350px');
			}
		});
		$('#paginado-TM .jPag-pages').css('width', '350px');
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

	function validar_filtro_busqueda(div) {

		valido = true;

		//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		marcarojo($("#dni"));
		marcarojo($("#nroTjta"));

		if (!valido) {
			$(".div_tabla_detalle").fadeOut("fast");
			$("#mensajeError").html("Debe ingresar datos numéricos");
			$("#mensajeError").fadeIn("fast");
		} else {
			$("#mensajeError").fadeOut("fast");
		}

		return valido;
	}

	function marcarojo($elem) {
		if ($elem.val() !== "") {
			if (!validarNumerico($elem.val())) {
				valido = false;
				$elem.attr("style", "border-color:red");
			} else {
				$elem.attr("style", "");
			}
		} else {
			$elem.attr("style", "");
		}
	}

	function validarNumerico(valor) {
		if (valor.match(/^[0-9]*$/)) {
			return true;
		} else {
			return false;
		}
	}


	function resettOp(selected) {

		$('.monto').each(function () {
			este = $(this).parents('tr').attr('tjta');
			if (este !== selected) {
				$(this).val('');
			}
		});

	}


	// MOSTRAR EL SALDO DISPONIBLE PARA CADA TARJETA LUEGO DE CONSULTAR
	function mostrar_saldo(data) {

		$.each(data.listadoTarjetas.lista, function (k, t) {
			if (t.saldos !== undefined)
				$('#saldo' + t.noTarjetaConMascara.replace(/[*]/g, "")).text((t.saldos.disponible));
		});

	}

	function mostrar_estatus(data) {

		$.each(data.listadoTarjetas.lista, function (k, t) {

			if (t.rc == "0") {
				t.rc = 'OK';
			} else {
				t.rc = 'Fallo';
				serv_var.fallidas += 1;
			}

			$('#estatus' + t.noTarjetaConMascara.replace(/[*]/g, "")).text(t.rc);
		});

	}


	// MARCAR CHECKBOX CUANDO INGRESA MONTO
	$('#resultado-tarjetas').on('keyup', '.monto', function () {

		if ($(this).val() != '') {

			$.each($(this).parents('tr').find(':checkbox'), function () {
				this.checked = 1;
			});

			if (toFormat($(this).val()) < toFormat(serv_var.maestroParam.montoMinTransDia)) { // validacion de monto minimo
				$(this).showBalloon({
					position: 'right',
					contents: 'monto minímo: ' + serv_var.maestroParam.montoMinTransDia
				});
			} else if (((isoPais == 'Pe' || isoPais == 'Usd') && !$(this).val().match(/^-?[0-9]+([\.][0-9]{0,2})?$/)) || ((isoPais == 'Ve' || isoPais == 'Co') && !$(this).val().match(/^-?[0-9]+([\,][0-9]{0,2})?$/))) { // validacion solo numeros reales
				$(this).val("");
				$.each($(this).parents('tr').find(':checkbox'), function () {
					this.checked = 0;
				});
			} else {
				$(this).hideBalloon();
			}

		} else {
			$(this).hideBalloon();
			$.each($(this).parents('tr').find(':checkbox'), function () {
				this.checked = 0;
			});
		}

	});


	// SUMAR LOS MONTOS INGRESADOS Y VALIDAR MONTO MAX. Y MIN.

	function calcularTrans(operacion) {

		var sum = 0,
			comision = 0,
			trans;
		serv_var.monto = [];
		serv_var.noTarjetas = "";
		serv_var.dni_tarjetas = "";

		switch (operacion) {
			case '20':
				trans = 'Abono';
				break;
			case '40':
				trans = 'Cargo';
				break;
		}

		$.each($('.monto'), function (k, v) {

			v = toFormat($(this).val());

			montoMinDia = toFormat(serv_var.maestroParam.montoMinTransDia);


			if (v && v >= montoMinDia) {
				sum += v;
				//serv_var.monto.push(toFormatSend(v));
				serv_var.monto.push(v);
				serv_var.noTarjetas += $(this).parents('tr').attr('tjta') + ",";
				serv_var.dni_tarjetas += $(this).parents('tr').attr('id_ext_per') + ",";

			} else {
				$(this).val('');
				$.each($(this).parents('tr').find(':checkbox'), function () {
					this.checked = 0;
				});
			}

		});

		if (sum) {

			serv_var.noTarjetas = serv_var.noTarjetas.substr(0, serv_var.noTarjetas.lastIndexOf(','));
			serv_var.noTarjetas = serv_var.noTarjetas.split(',');
			serv_var.dni_tarjetas = serv_var.dni_tarjetas.substr(0, serv_var.dni_tarjetas.lastIndexOf(','));
			serv_var.dni_tarjetas = serv_var.dni_tarjetas.split(',');


			var cantxdia = 0;
			var tjtas = serv_var.noTarjetas.length;
			serv_var.cantXdia.filter(function (op) {
				if (op.operacion == operacion) {
					cantxdia = parseInt(op.idCuenta, 10)
				}
			});
			var maxTransDia = parseInt(serv_var.maestroParam.cantidadMaxTransDia, 10);


			var acumSem = 0; // monto acumulado en la semana
			serv_var.acumXsem.filter(function (op) {
				if (op.operacion == operacion) {
					acumSem = toFormat(op.montoOperacion)
				}
			});

			comision = toFormat(serv_var.maestroParam.costoComisionTrans) * serv_var.noTarjetas.length; // comision total por las trans

			if ((cantxdia + tjtas) > maxTransDia) { // validaciones de cantidad trans por dia
				var canvas = "<h6>" + trans + "s realizados en el día: " + cantxdia + "</h6>";
				canvas += "<h6>" + trans + "s a realizar: " + tjtas + "</h6>";
				canvas += "<h5>" + trans + "s máximos por día: " + maxTransDia + "</h5>";

				notificacion('Exceso de Transacciones', canvas);
				return false;

			} else if (sum > toFormat(serv_var.maestroParam.montoMaxTransDia)) { // validar montos (diario y semanal)
				notificacion(trans + ' a tarjeta', '<h2>Ha excedido el monto diario</h2> <h6>Monto ' + trans + ': ' + toFormatShow(sum) + '</h6><h6>Monto permitido: ' + toFormatShow(serv_var.maestroParam.montoMaxTransDia) + '</h6>')
				return false;

			} else if ((sum + acumSem) > toFormat(serv_var.maestroParam.montoMaxTransSemanal)) {
				notificacion(trans + ' a tarjeta', '<h2>Ha excedido el monto semanal</h2> <h6>Monto ' + trans + ': ' + toFormatShow(sum) + '</h6><h6>Monto permitido: ' + toFormatShow(serv_var.maestroParam.montoMaxTransSemanal - acumSem) + '</h6>')
				return false;

			} else if ((sum + comision) > toFormat(serv_var.saldoDispon) && operacion == '20') { // si saldo disponible para abono
				notificacion(trans + ' a tarjeta', '<h2>Ha excedido el saldo disponible</h2> <h6>Monto ' + trans + ' mas comisión: ' + toFormatShow(sum + comision) + '</h6><h6>Saldo disponible: ' + toFormatShow(serv_var.saldoDispon) + '</h6>')
				return false;

			} else if (sum < toFormat(serv_var.maestroParam.montoMinTransDia)) {
				notificacion(trans + ' a tarjeta', '<h2>Monto minímo permitido: </h2>' + toFormatShow(serv_var.maestroParam.montoMinTransDia));
				return false;

			} else if (comision > toFormat(serv_var.saldoDispon) && operacion == '40') {
				notificacion(trans + ' a tarjeta', '<h2>Saldo no disponible</h2> <h6>Comisión ' + trans + ': ' + toFormatShow(comision) + '</h6><h6>Saldo disponible: ' + toFormatShow(serv_var.saldoDispon) + '</h6>')
				return false;

			} else {
				return true;
			}


		} else {
			notificacion(trans + ' a tarjeta', 'Ingrese el monto');
			return false;
		}

	}

	function calcularConsulta() {

		var comision = serv_var.maestroParam.costoComisionCons * serv_var.noTarjetas.length; // comision total por las consulta

		// validar comisiones de la consulta
		if (comision > toFormat(serv_var.saldoDispon)) {
			notificacion('Consulta a tarjeta', '<h2>Saldo no disponible</h2> <h6>Comisión consulta: ' + comision + '</h6><h6>Saldo disponible: ' + serv_var.saldoDispon + '</h6>')
			return false;
		}


		// validar cantidad de consultas al dia
		var cantxdia = 0; // cantidad de consultas acumuladas en el día.
		serv_var.cantXdia.filter(function (op) {
			if (op.operacion == '30') {
				cantxdia = parseInt(op.idCuenta, 10)
			}
		}); // obtener cantidad de operaciones que ha realizado en el día.
		var tjtas = serv_var.noTarjetas.length;

		if (tjtas <= parseInt(serv_var.maestroParam.cantidadTarjetaConsDia) &&
			(cantxdia + tjtas <= parseInt(serv_var.maestroParam.cantidadMaxConsDia, 10))) {

			return true;

		} else {
			var canvas = "<h6>Cantidad consultas realizadas en el día: " + cantxdia + "</h6>";
			canvas += "<h5>Cantidad máx. consultas en el día: " + serv_var.maestroParam.cantidadMaxConsDia + "</h5>";
			canvas += "<h6>Cantidad tarjetas a consultar: " + tjtas + "</h6>";
			canvas += "<h5>Cantidad máx. consultas por petición: " + serv_var.maestroParam.cantidadTarjetaConsDia + "</h5>";

			notificacion('Exceso de consultas', canvas);
			return false;
		}

		return true;

	}

	toFormat = function (valor) {

		valor = valor.toString();

		if (valor.length > 0) {

			if (isoPais == 'Pe' || isoPais == 'Usd') {

				return parseFloat(valor.replace(',', ''));

			} else if (isoPais == 'Ve' || isoPais == 'Co') {

				if (valor.substr(-3, 1) == ",")
					valor = valor.replace(/\./g, "").replace(/,/g, ".");
				else if (valor.substr(-3, 1) == ".")
					valor = valor.replace(/\,/g, "");
				else
					valor = valor.replace(/\,/g, "").replace(/\./g, "");

				valor = parseFloat(valor);

				return valor;

			}
		} else {
			return 0.00;
		}

	}

	toFormatShow = function (valor) {

		valor = valor.toString();

		if (isoPais == 'Pe' || isoPais == 'Usd') {

			return valor;

		}
		if (isoPais == 'Ve' || isoPais == 'Co') {

			valor = toFormat(valor);

			return formatoNumero(valor, 2, ",", ".");

		}
	}

	/**
	 * Da formato a un número para su visualización
	 *
	 * @param {(number|string)} numero Número que se mostrará
	 * @param {number} [decimales=null] Nº de decimales (por defecto, auto); admite valores negativos
	 * @param {string} [separadorDecimal=","] Separador decimal
	 * @param {string} [separadorMiles=""] Separador de miles
	 * @returns {string} Número formateado o cadena vacía si no es un número
	 *
	 * @version 2014-07-18
	 */
	function formatoNumero(numero, decimales, separadorDecimal, separadorMiles) {
		var partes, array;
		if (!isFinite(numero) || isNaN(numero = parseFloat(numero))) {
			return "";
		}
		if (typeof separadorDecimal === "undefined") {
			separadorDecimal = ",";
		}
		if (typeof separadorMiles === "undefined") {
			separadorMiles = "";
		} // Redondeamos
		if (!isNaN(parseInt(decimales))) {
			if (decimales >= 0) {
				numero = numero.toFixed(decimales);
			} else {
				numero = (Math.round(numero / Math.pow(10, Math.abs(decimales))) * Math.pow(10, Math.abs(decimales))).toFixed();
			}
		} else {
			numero = numero.toString();
		} // Damos formato
		partes = numero.split(".", 2);
		array = partes[0].split("");
		for (var i = array.length - 3; i > 0 && array[i - 1] !== "-"; i -= 3) {
			array.splice(i, 0, separadorMiles);
		}
		numero = array.join("");
		if (partes.length > 1) {
			numero += separadorDecimal + partes[1];
		}
		return numero;
	}


}); //FIN DOCUMENT READY
