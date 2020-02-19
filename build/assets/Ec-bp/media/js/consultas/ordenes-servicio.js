$(function () {

	$('#lotes-general').show();

	if ($("#msg").val()) {
		notificacion("ADVERTENCIA", $("#msg").val());
	}

	COS_var = {
		fecha_inicio: "",
		fecha_fin: "",
		loteTipo: "",
		fecIsend: "",
		fecfsend: "",
		tablaOS: null,
		tablaOSNF: null
	}

	$("#tabla-datos-general").find(".OSinfo").hide(); // ocultar lotes de os

	// MOSTRAR/OCULTAR LOTES SEGUN OS
	$("#tabla-datos-general").on("click", "#ver_lotes", function () {

		var OS = $(this).parents("tr").attr('id');
		var $lotes = $("#tabla-datos-general").find("." + OS);

		$lotes.is(":visible") ? $lotes.fadeOut("slow") : $lotes.fadeIn("slow");
		$('.OSinfo').not("." + OS).hide();
		showOptions();

	});

	//RECEPCION DE TARJETAS
	// $("#tabla-datos-general").on("click","#res",function(code) {
	// 	var nlote = $(this).attr('data-id')
	// 	var ceo_cook = decodeURIComponent(
	// 		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	// 	);
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: baseURL+isoPais+'/consulta/embozado',
	// 		data: { 'nlote': nlote, ceo_name: ceo_cook },
	// 		beforeSend: function() {
	// 			$("#loading").dialog({
	// 				title: 'Enviando notificación',
	// 				modal: true,
	// 				draggable: false,
	// 				rezise: false,
	// 				open: function(event, ui) {
	// 					$('.ui-dialog-titlebar-close', ui.dialog).hide();
	// 				}
	// 			});
	// 		},
	// 		success: function (res) {

	// 			$("#loading").dialog('destroy');
	// 			$('#msg-certificate-notifi').dialog({
	// 				title: 'Notificación enviada',
	// 				modal: true,
	// 				draggable: false,
	// 				rezise: false,
	// 				open: function(event, ui) {
	// 					$('.ui-dialog-titlebar-close', ui.dialog).hide();

	// 					$('#msg-info').html('<p>' + res.msg + '</p>')
	// 				}

	// 			})
	// 			$('#close-info').on('click', function(){
	// 				$('#msg-certificate-notifi').dialog('close');
	// 				switch(res.code) {
	// 					case 0:
	// 						window.location.reload();
	// 						break;
	// 					case 3:
	// 						window.location.href = baseURL+isoPais+'/logout'
	// 						break;
	// 				}
	// 			});
	// 		}
	// 	});
	// });
	// EVENTO BUSCAR OS SEGUN FILTRO
	$("#buscarOS").on("click", function () {
		var statuLote = $("#status_lote").val();
		if (statuLote !== '' && COS_var.fecha_inicio !== '' && COS_var.fecha_fin !== '') {
			var form = $('#form-criterio-busqueda');
			validateForms(form);
			if (form.valid()) {
				if (Date.parse(COS_var.fecha_fin) >= Date.parse(COS_var.fecha_inicio)) {

					$aux = $("#loading").dialog({

						dialogClass: "hide-close",
						title: 'Buscando orden de servicio', modal: true, close: function () { $(this).dialog('destroy') }, resizable: false });

					var ceo_cook = decodeURIComponent(
						document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
					);

					$('form#formulario').empty();
					$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
					$('form#formulario').append('<input type="hidden" name="data-fechIn" value="' + COS_var.fecIsend + '" />');
					$('form#formulario').append('<input type="hidden" name="data-fechFin" value="' + COS_var.fecfsend + '" />');
					$('form#formulario').append('<input type="hidden" name="data-status" value="' + statuLote + '" />');
					$('form#formulario').attr('action', baseURL + isoPais + "/consulta/ordenes-de-servicio");
					$('form#formulario').submit();

				} else {
					notificacion('Buscar orden de servicio', 'Rango de fecha Incoherente');
				}
			} else {
				notificacion("Buscar orden de servicio", "Verifica los datos ingresados e intenta nuevamente");
			}
		} else {
			notificacion("Buscar orden de servicio", "<h2>Verifica que:</h2><h6>1. Has seleccionado un rango de fechas.</h6><h6>2. Has seleccionado un estatus de lote.</h6>")
		}
	});

	$("tbody").on("click", ".viewLo", function () {  // ver detalle de lote

		var idLote = $(this).attr('id');

		$('form#detalle_lote').append('<input type="hidden" name="data-lote" value="' + idLote + '" />');
		$("#detalle_lote").submit();

	});


	$("#tabla-datos-general").on("click", "#dwnPDF", function () { // descargar orden de servicio

		var OS = $(this).parents("tr").attr('id');
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$aux = $("#loading").dialog({

			dialogClass: "hide-close",
			title: 'Descargando archivo PDF', modal: true,
			close: function () {
				$(this).dialog('close')
			},buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
					$(this).dialog("close");
					}
				}
			},
			resizable: false });
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
		$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + OS + '" />');
		$('form#formulario').append($('#data-OS'));
		$('form#formulario').attr('action', baseURL + api + isoPais + "/consulta/downloadOS");
		$('form#formulario').submit();
		setTimeout(function () { $aux.dialog('destroy') }, 8000);

	});

	$(":radio").on("change", function () {
		$("#fecha_inicial").val('').addClass('ignore', true);
		$("#fecha_final").val('').addClass('ignore', true);
		$(this).removeClass('ignore');
		var dias = $(this).val();

		var hoy = new Date();
		var resta = new Date(hoy.getTime() - (dias * 24 * 3600 * 1000));

		COS_var.fecha_inicio = (resta.getMonth() + 1) + "/" + resta.getDate() + "/" + resta.getFullYear();
		COS_var.fecIsend = resta.getDate() + "/" + (resta.getMonth() + 1) + "/" + resta.getFullYear();

		COS_var.fecha_fin = (hoy.getMonth() + 1) + "/" + hoy.getDate() + "/" + hoy.getFullYear();
		COS_var.fecfsend = hoy.getDate() + "/" + (hoy.getMonth() + 1) + "/" + hoy.getFullYear();


	});


	calendario("fecha_inicial");
	calendario("fecha_final");


	function notificacion(titu, msj) {
		var canvas = "<div>" + msj + "</div>";
		$(canvas).dialog({

			dialogClass: "hide-close",
			title: titu,
			modal: true,
			close: function () { $(this).dialog('destroy') },
			resizable: false,
			buttons: {
				"Aceptar":{
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
						$(this).dialog('destroy');
					}
				}

			}
		});
	}


	function calendario(input) {

		$("#" + input).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			dateFormat: "dd/mm/yy",
			maxDate: "+0D",
			onClose: function (selectedate) {
				if (input == 'fecha_inicial' && selectedate) {
					$("#fecha_final").datepicker('option', 'minDate', selectedate);
				} else if (input == 'fecha_inicial') {
					$("#fecha_final").datepicker('option', 'minDate', "");
				}
				if (input == 'fecha_final' && selectedate) {
					$("#fecha_inicial").datepicker('option', 'maxDate', selectedate);
				} else if (input == 'fecha_inicial') {
					$("#fecha_inicial").datepicker('option', 'maxDate', "+0D");
				}
				if ($("#fecha_inicial").val() != '' && $("#fecha_final").val() != '') {
					$.each($(":radio"), function () {
						this.checked = 0;
						$(this).addClass('ignore');
					});
					var aux = $("#fecha_inicial").val().split('/');
					COS_var.fecha_inicio = aux[1] + "/" + aux[0] + "/" + aux[2];
					COS_var.fecIsend = $("#fecha_inicial").val();

					aux = $("#fecha_final").val().split('/');
					COS_var.fecha_fin = aux[1] + "/" + aux[0] + "/" + aux[2];
					COS_var.fecfsend = $("#fecha_final").val();
					$("#fecha_inicial").removeClass('ignore');
					$("#fecha_final").removeClass('ignore');
				}
			}
		});
	}
	var paginar = function ($tabla) {
		var tabla = $tabla.dataTable({
			"iDisplayLength": 10,
			'bDestroy': true,
			"sPaginationType": "full_numbers",
			"aaSorting": [],
			"oLanguage": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados.",
				"sEmptyTable": "Ningún dato disponible en esta tabla.",
				"sInfo": "Mostrando registros del _START_ al _END_, de un total de _TOTAL_ registro(s).",
				"sInfoEmpty": "Mostrando registros del 0 al 0, de un total de 0 registro(s).",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registro(s))",
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
		return tabla;
	}

	COS_var.tablaOS = paginar($('#tabla-datos-general'));
	COS_var.tablaOSNF = paginar($('#tablelotesNF'));

	showOptions();
	$('.paging_full_numbers').on('click', function () {
		showOptions();
	});

	function showOptions() {
		$("#tbody-datos-general table").css('margin-left', 30, 'important');
		//$('#tbody-datos-general tr').css('margin-left', 0);


	}


	$('#tabla-datos-general').on('click', '#anular', function () {

		var btnAnular = this;
		$item = $(this).parents('tr');
		var idOS = $(this).parents('tr').attr('id');

		var canvas = "<div id='dialog-confirm'>";
		canvas += "<p>Orden nro.: " + idOS + "</p>";
		canvas += "<fieldset>";
		canvas += "<form id='anular-os-form'  name='anular-os-form'>"
		canvas += "<input type='password' id='pass' size=30 placeholder='Ingresa tu contraseña' class='text ";
		canvas += "ui-widget-content ui-corner-all'>";
		canvas += "<h5 id='msg'></h5>";
		canvas += "</form>";
		canvas += "</fieldset>";
		canvas += "</div>";

		var pass;

		$(canvas).dialog({

			dialogClass: "hide-close",
			title: 'Anular Orden de Servicio',
			modal: true,
			resizable: false,
			buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
					$(this).dialog("close");
					}
				}
			},
			close: function () { $(this).dialog("destroy"); },
			buttons: {
				"Anular": {
					text: 'Anular',
					class: 'novo-btn-primary-modal',
					click: function () {
						pass = $(this).find('#pass').val();
					if (pass !== "") {
						pass = hex_md5(pass);
						$('#pass').val('');
						$(this).dialog('destroy');
						var $aux = $('#loading').dialog({

							dialogClass: "hide-close",
							title: 'Anulando Orden de Servicio', modal: true, resizable: false,
							buttons: {
								"Aceptar": {
									text: 'Aceptar',
									class: 'novo-btn-primary-modal',
									click: function () {
									$(this).dialog("close");
									}
								}
							},
							close: function () { $aux.dialog('close');
							}
						});
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						var dataRequest = JSON.stringify({
							data_idOS: idOS,
							data_pass: pass
						})
						dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
						$consulta = $.post(baseURL + api + isoPais + "/consulta/anularos", { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) });
						$consulta.done(function (response) {
							data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
							$aux.dialog('destroy');
							if (!data.ERROR) {
								notificacion("Anulando Orden de Servicio", 'Anulación exitosa');

								COS_var.tablaOS.fnDeleteRow(COS_var.tablaOS.fnGetPosition(btnAnular.parentNode.parentNode));

							} else {
								if (data.ERROR == '-29') {

									alert('Usuario actualmente desconectado'); location.reload();
								} else {
									notificacion("Anulando Orden de Servicio", data.ERROR);
								}
							}
						});
					} else {
						$(this).find($('#msg')).text('Debes ingresar tu contraseña');
					}
					}
				}
			}
		});
	});

	$('#tabla-datos-general').on('click', '#pagoCo', function (e) {
		var btnPagarOS = this;
		e.preventDefault();
		var idOS = $(this).parents('tr').attr('id'),
			totalamount = $(this).closest('tr').find('#montoDeposito').text(),
			factura = $(this).closest('tr').find('#facturaOS').text(),
			$aux = $('#loading').dialog({

				dialogClass: "hide-close",
				title: 'Enviando código de seguridad',
				modal: true,
				resizable: false,
				draggable: false,
				open: function (event, ui) {
					$('.ui-dialog-titlebar-close', ui.dialog).hide();
				}
			});

		$.get(baseURL + api + isoPais + '/consulta/PagoOS').done(function (response) {
			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
			$aux.dialog('destroy');
			switch (data.code) {
				case 0:
					var canvas = "<div id='dialog-confirm'>";
					canvas += "<p>Orden nro.: " + idOS + "</p>";
					canvas += "<fieldset><input type='text' id='token-code' size=30 placeholder='Ingresa el código' class='text ui-widget-content ui-corner-all'/>";
					canvas += "<h5 id='msg'></h5></fieldset></div>";

					$(canvas).dialog({

						dialogClass: "hide-close",
						title: data.title,
						modal: true,
						resizable: false,
						draggable: false,
						close: function () {
							$(this).dialog("destroy");
						},
						buttons: {
							"Procesar": {
								text: 'Procesar',
								class: 'novo-btn-secondary-modal',
								click: function () {
									var codeToken = $("#token-code").val();
								if (codeToken != '') {
									$("#token-code").val('');
									$(this).dialog('destroy');
									$aux = $('#loading').dialog({

										dialogClass: "hide-close",
										title: 'Procesando',
										modal: true,
										resizable: false,
										draggable: false,
										open: function (event, ui) {
											$('.ui-dialog-titlebar-close', ui.dialog).hide();
										}
									});
									var ceo_cook = decodeURIComponent(
										document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
									);
									var dataRequest = JSON.stringify({
										idOS: idOS,
										codeToken: codeToken,
										totalamount: totalamount,
										factura: factura
									})
									dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
									$.post(baseURL + api + isoPais + '/consulta/PagoOSProcede', { request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) })
										.done(function (response) {
											data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
											$aux.dialog('destroy');
											switch (data.code) {
												case 0:
													notiPagOS(data.title, data.msg, 'ok');
													COS_var.tablaOS.fnDeleteRow(COS_var.tablaOS.fnGetPosition(btnPagarOS.parentNode.parentNode.parentNode));
													break;
												case 1:
													notiPagOS(data.title, data.msg, 'error');
													(data.errorReg == 1) ? COS_var.tablaOS.fnDeleteRow(COS_var.tablaOS.fnGetPosition(btnPagarOS.parentNode.parentNode.parentNode)) : '';
													break;
												case 2:
												default:
													notiPagOS(data.title, data.msg, 'close');
											}


										})
								} else {
									$(this).find($('#token-code').css('border-color', '#cd0a0a'));
									$(this).find($('#msg')).text('Debes ingresar el código de seguridad enviado a tu correo');
								}
								}
							}
						}
					});
					break;
				case 1:
					notiPagOS(data.title, data.msg, 'error');
					break;
				case 2:
				default:
					notiPagOS(data.title, data.msg, 'close');
			}
		});
	});

	$("#tabla-datos-general").on("click", "#factura", function () {
		orden = $(this).parents("tr").attr("id");
		$(this).removeAttr("href");
		$(this).removeAttr('target');
		$aux = $("#loading").dialog({

			dialogClass: "hide-close",
			title: 'Descargando factura', modal: true,
			buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
					$(this).dialog("close");
					}
				}
			}, resizable: false });
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
		$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + orden + '" />');
		$('form#formulario').append($('#data-OS'));
		$('form#formulario').attr('action', baseURL + api + isoPais + "/consulta/facturar");
		$('form#formulario').submit();
		setTimeout(function () { $aux.dialog('destroy') }, 8000);
	});

}); // fin document ready

function notiPagOS(titu, msg, type) {
	var canvas = "<div style='text-align: center;'>" + msg + "</div>";
	$(canvas).dialog({

		dialogClass: "hide-close",
		title: titu,
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
				$(this).dialog("close");
				}
			}
		}
	});
}
