$(function () {
	$('#lotes-general').show();

	if (parseInt($('#cantXauth').val()) <= 0) {
		$('#select-allA').hide();
	} else {
		$('.listaxAuth #check-all').append('<em>Seleccionar todos (' + $('#cantXauth').val() + ' lotes)</em>');
	}
	if (!$("#loteXdesa").val() && !$('#lotesxAuth').val()) {
		$(".listaxAuth .checkbox-select").remove();
	}

	var js_var = {
		loteF: "",
		numloteF: "",
		tipoloteF: "",
		loteA: "",
		numloteA: "",
		tipoloteA: ""
	}

	//LOTE POR FIRMAR
	$('#lotes-2').on('click', '#select-allF', function () {
		$all = this.checked;

		if ($(this).is(':checked')) {
			js_var.loteF = "";
			js_var.numloteF = "";
			js_var.tipoloteF = "";
		}

		selectAllFirm();
	});


	$('#lotes-2').on('click', '#check-oneF', function () {
		if ($(this).is(':checked')) {

			js_var.loteF += $(this).attr('value') + ",";
			js_var.numloteF += $(this).attr('numlote') + ",";
			js_var.tipoloteF += $(this).attr('ctipolote') + ",";
		} else {
			js_var.loteF = js_var.loteF.replace($(this).attr('value') + ",", "");
			js_var.numloteF = js_var.numloteF.replace($(this).attr('numlote') + ",", "");
			js_var.tipoloteF = js_var.tipoloteF.replace($(this).attr('ctipolote') + ",", "");
		}

	});


	//BOTON FIRMAR LOTE
	$('#lotes-2').on('click', '#firma', function () {

		var pass = $('#clave').val();

		if (pass != "" && js_var.loteF != "") {
			pass = hex_md5(pass);
			$('#clave').val('');
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var $aux = $('#loading').dialog({
				title: "Firmando lote",
				modal: true,
				bgiframe: true,
				dialogClass: 'hide-close'
			});

			$.post(baseURL + isoPais + '/lotes/autorizacion/firmar', {
				'data-lotes': js_var.loteF,
				'data-pass': pass,
				ceo_name: ceo_cook
			}).done(function (data) {
				$aux.dialog('destroy');
				if (!data.ERROR) {
					$('<div>Proceso exitoso.<h5>Listando lotes</h5></div>').dialog({
						title: "Firmando lote",
						modal: true,
						bgiframe: true
					});
					location.reload();
				} else {
					if (data.ERROR == '-29') {
						alert('Usuario actualmente desconectado');
						location.reload();
					} else {
						notificacion('Firmando lote', data.ERROR);
					}
				}

			});
		} else {
			notificacion("Firmando lotes", "Seleccione al menos un lote e ingrese su contraseña");
		}

	});


	//LOTES POR AUTORIZAR
	$('#lotes-2').on('click', '#select-allA', function () {
		$all = $(this);

		if ($all.is(':checked')) {
			js_var.loteA = "";
			js_var.numloteA = "";
			js_var.tipoloteA = "";
		}

		selectAllAuth();

	});


	$('#lotes-2').on('click', '#check-oneA', function () {

		if ($(this).is(':checked')) {
			js_var.loteA += $(this).attr('value') + ",";
			js_var.numloteA += $(this).attr('numlote') + ",";
			js_var.tipoloteA += $(this).attr('ctipolote') + ",";

		} else {
			js_var.loteA = js_var.loteA.replace($(this).attr('value') + ",", "");
			js_var.numloteA = js_var.numloteA.replace($(this).attr('numlote') + ",", "");
			js_var.tipoloteA = js_var.tipoloteA.replace($(this).attr('ctipolote') + ",", "");

		}
	});

	$('#lotes-2').on('click', '#button-autorizar', function () {
		var
			pass = $('#claveAuth').val(),
			osTipo = $('#selec_tipo_lote').val(),
			select_modal = $("#select-modal").val(),
			nuevo_iva = $("#nuevo-iva").val();

		if (nuevo_iva == 1) {
			if (select_modal == "") {
				$("#modal-lote").dialog({
					title: 'Seleccione modalidad de pago',
					modal: true,
					resizable: false,
					draggable: false,
					width: 320,
					close: function (evt, ui) {
						$('input[name="modalidad"]').prop('checked', false);
						$('input[name="modalidad"]').first().prop('checked', true);
					},
					buttons: {
						OK: function () {

							var select_modal = $('input[name="modalidad"]:checked').val(),
								nuevo_iva = $("#nuevo-iva").val();

							$(this).dialog('destroy');
							envioAjaxAutorizar(baseURL, isoPais, js_var, pass, osTipo, select_modal, nuevo_iva);
						}
					}
				});
			}
		} else {
			envioAjaxAutorizar(baseURL, isoPais, js_var, pass, osTipo, select_modal, nuevo_iva);
		}
	});

	$('#lotes-2').on('click', '#button-eliminar', function () { //eliminar en autorizar

		var pass = $('#claveAuth').val();

		if (pass != "" && js_var.loteA != "") {

			pass = hex_md5(pass);
			$('#claveAuth').val('');

			eliminarLotes(js_var.loteA, js_var.numloteA, js_var.tipoloteA, pass);

		} else {
			notificacion("Autorizando lotes", "Seleccione al menos un lote e ingrese su contraseña");
		}

	});


	$('#lotes-2').on('click', '.icon-desa', function () {
		$item = $(this);

		var acnumlote = [$(this).attr('numlote')];

		var canvas = "<div id='dialog-confirm'>";
		canvas += "<p>Lote Nro.: " + acnumlote + "</p>";
		canvas += "<fieldset><input type='password' id='pass' placeholder='Ingrese su contraseña' size=30/>";
		canvas += "</fieldset><h5 id='msg'></h5></div>";

		var pass;
		var idlote = $(this).attr('idlote') + ",";

		$(canvas).dialog({
			title: "Desasociar firma de lote",
			modal: true,
			bgiframe: true,
			close: function () {
				$(this).dialog('destroy');
			},
			buttons: {
				Desasociar: function () {

					pass = $(this).find($('#pass')).val();

					if (pass !== "") {
						pass = hex_md5(pass);
						$('#pass').val('');
						$(this).dialog('destroy');
						var $aux = $('#loading').dialog({
							title: "Desasociar firma de lote",
							modal: true,
							bgiframe: true
						});
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						$.post(baseURL + isoPais + '/lotes/autorizacion/desasociar', {
							'data-lotes': idlote,
							'data-pass': pass,
							ceo_name: ceo_cook
						}).done(function (data) {
							$aux.dialog('destroy');
							if (!data.ERROR) {

								notificacion('Desasociar firma de lote', 'Revocación de firma exitosa');

								location.reload();

							} else {
								if (data.ERROR == '-29') {
									alert('Usuario actualmente desconectado');
									location.reload();
								} else {
									notificacion('Desasociar firma de lote', data.ERROR);
								}
							}

						});

					} else {
						$(this).find($('#msg')).text("Debe ingresar su contraseña");
					}


				}
			}
		});

	});


	$('#lotes-2').on('click', '#eliminarF', function () { // boton eliminar en firma

		var pass = $('#clave').val();

		if (pass != "" && js_var.loteF != "") {

			pass = hex_md5(pass);
			$('#clave').val('');

			eliminarLotes(js_var.loteF, js_var.numloteF, js_var.tipoloteF, pass)

		} else {
			notificacion("Eliminar Lote", "Seleccione al menos un lote e ingrese su contraseña");
		}

	});


	$('#lotes-2').on('click', '#borrar', function () {
		$item = $(this);

		var pass;
		var idlote = $(this).attr('idlote') + ",";
		var acnumlote = $(this).attr('numlote') + ",";
		var ctipolote = $(this).attr('ctipolote') + ",";

		var canvas = "<div id='dialog-confirm'>";
		canvas += "<p>Lote Nro.: " + acnumlote + "</p>";
		canvas += "<fieldset><input type='password' id='pass' size=30 placeholder='Ingrese su contraseña' class='text ui-widget-content ui-corner-all'/>";
		canvas += "<h5 id='msg'></h5></fieldset></div>";
		tabla = $(this).parents('table').attr('id');

		if ((!$('#clave').val() && tabla == 'table-firmar') || (!$('#claveAuth').val() && tabla == 'table-auth')) {

			$(canvas).dialog({
				title: "Eliminar Lote",
				modal: true,
				position: {
					my: "center top",
					at: "center 500"
				},
				bgiframe: true,
				close: function () {
					$(this).dialog('destroy');
				},
				buttons: {
					Eliminar: function () {

						pass = $(this).find($('#pass')).val();


						if (pass !== "") {

							pass = hex_md5(pass);
							$('#pass').val('');
							$(this).dialog('destroy');

							eliminarLotes(idlote, acnumlote, ctipolote, pass);

						} else {
							$(this).find($('#msg')).text("Debe ingresar su contraseña");
						}

					}
				}
			});

		} else {

			if ($('#clave').val() != '' && tabla == 'table-firmar') {
				pass = hex_md5($('#clave').val());
			} else if ($('#claveAuth').val() != '' && tabla == 'table-auth') {
				pass = hex_md5($('#claveAuth').val());
			}

			eliminarLotes(idlote, acnumlote, ctipolote, pass);

		}

	});


	$('#lotes-2').on('click', '#detalle', function () { // autorizacion/detalleAuth
		var lote = $(this).attr('idlote');

		$(':checkbox').each(function () {
			this.checked = 0;
		});

		$("form#detalleAuth").append('<input type="hidden" name="data-lote" value="' + lote + '" />');
		$("form#detalleAuth").submit();
	});

	//PAGINACION DETALLE LOTE AUTORIZACION
	function toDataTable($table) {

		var dt = $table.dataTable({
			"iDisplayLength": 10,
			'bRetrieve': true,
			"sPaginationType": "full_numbers",
			"oLanguage": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_, de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0, de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "<<",
					"sLast": ">>",
					"sNext": ">",
					"sPrevious": "<"
				}
			}
		});

		return dt;
	}


	toDataTable($('#table-lote-detail'));
	toDataTable($('#table-firmar'));
	toDataTable($('#table-auth'));


	$('#table-auth_paginate').on('click', function () {

		selectAllAuth();

	});

	function selectAllAuth() {

		if ($('#select-allA').is(':checked')) {
			$('.listaxAuth').find($(':checkbox')).each(function () {
				if ($(this).attr('value') !== undefined) {
					js_var.loteA += $(this).attr('value') + ",";
					js_var.numloteA += $(this).attr('numlote') + ",";
					js_var.tipoloteA += $(this).attr('ctipolote') + ",";
				}
				this.checked = true;
			});
		} else {
			js_var.loteA = "";
			js_var.numloteA = "";
			js_var.tipoloteA = "";
			$('.listaxAuth').find($(':checkbox')).each(function () {
				this.checked = false;
			});
		}

	}

	$('#table-firmar_paginate').on('click', function () {

		selectAllFirm();

	});

	function selectAllFirm() {

		if ($('#select-allF').is(':checked')) {

			$('.lotes-contenedor-autorizacion').find($(':checkbox')).each(function () {
				if ($(this).attr('value') !== undefined) {
					js_var.loteF += $(this).attr('value') + ",";
					js_var.numloteF += $(this).attr('numlote') + ",";
					js_var.tipoloteF += $(this).attr('ctipolote') + ",";
				}
				this.checked = 1;
			});


		} else {
			js_var.loteF = "";
			js_var.numloteF = "";
			js_var.tipoloteF = "";
			$('.lotes-contenedor-autorizacion').find($(':checkbox')).each(function () {
				this.checked = 0;
			});
		}


	}




	function resetValuesFirm() {
		js_var.loteF = "";
		js_var.numloteF = "";
		js_var.tipoloteF = "";
		$.each($('.lotes-contenedor-autorizacion').find($(':checkbox')), function () {
			this.checked = 0;
		});
	}

	function resetValuesAuth() {
		js_var.loteA = "";
		js_var.numloteA = "";
		js_var.tipoloteA = "";
		$.each($('.listaxAuth').find($(':checkbox')), function () {
			this.checked = false;
		});
	}

	$.each($('.icon-desa'), function () {
		$(this).parents('tr').css('cursor', 'help');
		$(this).parents('tr').balloon({
			contents: $('#msg_2dafirma'),
			position: 'top'
		});
	});


	function eliminarLotes(idlote, acnumlote, ctipolote, pass) {
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var $aux = $('#loading').dialog({
			title: "Eliminando lote",
			modal: true,
			bgiframe: true,
			dialogClass: 'hide-close'
		});
		dataRequest = JSON.stringify({
			'data_lotes': idlote,
			'data_acnumlote': acnumlote,
			'data_ctipolote': ctipolote,
			'data_pass': pass,
		});
		dataRequest  = CryptoJS.AES.encrypt(dataRequest , ceo_cook, {format: CryptoJSAesJson}).toString();
		$.post(baseURL + isoPais + '/lotes/autorizacion/eliminarAuth', {
			request: dataRequest,
			ceo_name: ceo_cook,
			plot: btoa(ceo_cook)
			})
			.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
				$aux.dialog('destroy');
				if (!data.ERROR) {

					notificacion('Eliminando lote', 'Eliminación exitosa');

					location.reload();
				} else {
					if (data.ERROR == '-29') {
						alert('Usuario actualmente desconectado');
						location.reload();
					} else {

						notificacion('Eliminando lote', data.ERROR);
					}
				}

			});

	}

	$('#downPDF').on('click', function () {

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/detalleLoteAuthExpPDF");
		$('#data-lote').val($("#data-lote").val());
		$('#exportTo').submit();

	});

	$('#downXLS').on('click', function () {

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/detalleLoteAuthExpXLS");
		$('#data-lote').val($("#data-lote").val());
		$('#exportTo').submit();

	});

}); //Fin document ready

function envioAjaxAutorizar(baseURL, isoPais, js_var, pass, osTipo, select_modal, nuevo_iva) {
	var
		orderPayable = parseInt($('#table-auth').attr('order-payable')) + 'a';
	//se concatena la letra "a" para evitar de forma temporal la validación de la comisión de la orden de servicio

	if (js_var.tipoloteA.indexOf('2') !== -1 && Number.isInteger(orderPayable)) {
		var
			title = 'Orden de Servicio pendiente',
			msg = 'Antes de autorizar una nueva Orden, es indispensable que la OS Nº ' + orderPayable + ' ';
		msg += 'pendiente sea anulada o pagada<br><br>';
		msg += 'En caso de anulación, la tarifa por <b>Servicios Operativos y de logística</b> ';
		msg += 'será incluida en la siguiente Orden';

		notificacion(title, msg);
	} else {
		if (pass != "" && js_var.loteA != "" && osTipo != "") {

			pass = hex_md5(pass);
			$('#claveAuth').val('');

			$('#loading').dialog({
				title: 'Autorizando lotes...',
				modal: true,
				resizable: false,
				draggable: false,
				dialogClass: 'hide-close',
				close: function () {
					$(this).dialog('destroy')
				}
			});
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				dataRequest = JSON.stringify({
					data_lotes: js_var.loteA,
					data_pass: pass,
					data_tipoOS: osTipo,
				});
				dataRequest  = CryptoJS.AES.encrypt(dataRequest , ceo_cook, {format: CryptoJSAesJson}).toString();
			$.post(baseURL + isoPais + '/lotes/preliminar', {
				request: dataRequest,
				ceo_name: ceo_cook,
				plot: btoa(ceo_cook)
				})
				.done(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
					var
						code = data.code,
						title = data.title,
						msg = data.msg,
						dataCalc = data.data;
					$('#loading').dialog('destroy');
					if (code === 0) {
						$("#data-COS").attr('value', dataCalc);
						var
							modalMsg = '<div>';
						modalMsg += '<h3>Proceso exitoso</h3>';
						modalMsg += '<h5>Ha generado el cálculo de la orden de servicio.</h5>';
						modalMsg += '</div>';
						$(modalMsg).dialog({
							title: "Autorizando lotes",
							modal: true,
							resizable: false,
							draggable: false,
							dialogClass: 'hide-close',
							buttons: {
								Siguiente: function (event, ui) {
									$('#autorizacion').submit();
								}
							}
						});
					} else {
						notificacion(title, msg, code);
					}
				});
		} else {
			var
				mensajeNoti = '<h2>Verifica que:</h2>';
			mensajeNoti += '<h3>1. Ha seleccionado al menos un lote</h3>';
			mensajeNoti += '<h3>2. Has ingresado su contraseña</h3>';
			mensajeNoti += '<h3>3. Has seleccionado el tipo orden de servicio</h3>';

			notificacion("Autorizando lotes", mensajeNoti);
		}
	}
}

function notificacion(titulo, mensaje, code) {
	code = code !== undefined ? code : '';
	var canvas = "<div>" + mensaje + "</div>";

	$(canvas).dialog({
		title: titulo,
		modal: true,
		maxWidth: 700,
		maxHeight: 300,
		bgiframe: true,
		draggable: false,
		dialogClass: 'hide-close',
		buttons: {
			Aceptar: function () {
				$(this).dialog("close");
				if (code === 3) {
					$(location).attr('href', baseURL + isoPais + '/login');
				}
			}
		}
	});
}
