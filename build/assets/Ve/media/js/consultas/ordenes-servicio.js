$(function () {

	$('.OS-icon ').attr('style', 'display: none !important;');

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

	});


	// EVENTO BUSCAR OS SEGUN FILTRO
	$("#buscarOS").on("click", function () {
		var statuLote = $("#status_lote").val();
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		if (statuLote !== '' && COS_var.fecha_inicio !== '' && COS_var.fecha_fin !== '') {
			if (Date.parse(COS_var.fecha_fin) >= Date.parse(COS_var.fecha_inicio)) {
				$aux = $("#loading").dialog({
					title: 'Buscando Orden de Servicio',
					modal: true,
					resizable: false,
					draggable: false,
					open: function (event, ui) {
						$('.ui-dialog-titlebar-close', ui.dialog).hide()
					}
				});

				$('form#formulario').empty();
				$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
				$('form#formulario').append('<input type="hidden" name="data-fechIn" value="' + COS_var.fecIsend + '" />');
				$('form#formulario').append('<input type="hidden" name="data-fechFin" value="' + COS_var.fecfsend + '" />');
				$('form#formulario').append('<input type="hidden" name="data-status" value="' + statuLote + '" />');
				$('form#formulario').attr('action', baseURL + isoPais + "/consulta/ordenes-de-servicio");
				$('form#formulario').submit();
			} else {
				notificacion("Buscar Orden de Servicio", "Rango de fecha Incoherente");
			}
		} else {
			notificacion("Buscar Orden de Servicio", "<h2>Verifique que:</h2><h6>1. Ha seleccionado un rango de fechas</h6><h6>2. Ha seleccionado un estatus de lote</h6>")
		}
	});



	$("tbody").on("click", ".viewLo", function () { // ver detalle de lote

		var idLote = $(this).attr('id');
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#detalle_lote').append('<input type="hidden" name="data-lote" value="' + idLote + '" />');
		$('form#detalle_lote').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$("#detalle_lote").submit();

	});


	$("#tabla-datos-general").on("click", "#dwnPDF", function () { // descargar orden de servicio
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var OS = $(this).parents("tr").attr('id');
		$aux = $("#loading").dialog({
			title: 'Descargando archivo PDF',
			modal: true,
			close: function () {
				$(this).dialog('close')
			},
			resizable: false
		});
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + OS + '" />');
		$('form#formulario').append($('#data-OS'));
		$('form#formulario').attr('action', baseURL + api + isoPais + "/consulta/downloadOS");
		$('form#formulario').submit();
		setTimeout(function () {
			$aux.dialog('destroy')
		}, 8000);

	});


	$("#tabla-datos-general").on("click", "#pagoOS", function () { // pago orden de servicio
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var OS = $(this).parents("tr").attr('id');
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + OS + '" />');
		$('form#formulario').append($('#data-OS'));
		$('form#formulario').attr('action', baseURL + isoPais + "/consulta/registro-pago");
		$('form#formulario').submit();

	});


	$(":radio").on("change", function () {
		$("#fecha_inicial").val('');
		$("#fecha_final").val('');
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
					});
					var aux = $("#fecha_inicial").val().split('/');
					COS_var.fecha_inicio = aux[1] + "/" + aux[0] + "/" + aux[2];
					COS_var.fecIsend = $("#fecha_inicial").val();

					aux = $("#fecha_final").val().split('/');
					COS_var.fecha_fin = aux[1] + "/" + aux[0] + "/" + aux[2];
					COS_var.fecfsend = $("#fecha_final").val();
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
		return tabla;
	}

	COS_var.tablaOS = paginar($('#tabla-datos-general'));
	COS_var.tablaOSNF = paginar($('#tablelotesNF'));

	showOptions();
	$('.paging_full_numbers').on('click', function () {
		showOptions();
	});

	function showOptions() {
		$('#tabla-datos-general tr').hover(
			function () {

				if (!$(this).hasClass('OSinfo')) {
					$(this).find('.OS-icon').show();
					$(this).css('margin-left', 0);
				}
			},
			function () {

				var OS = $(this).attr('id');
				var $lotes = $("#tabla-datos-general").find("." + OS);

				if (!$(this).hasClass('OSinfo') && !$lotes.is(":visible")) {
					$(this).find('.OS-icon').hide();
					$(this).css('margin-left', 31);
				}
			}
		);
	}


	$('#tabla-datos-general').on('click', '#anular', function () {
		var
			btnAnular = this,
			idOS = $(this).parents('tr').attr('id'),
			aplicaCostD = $(this).parents('tr').attr('aplica-costo'),
			pass,
			canvas = '<div id="dialog-confirm">';
		canvas += '<p>Orden Nro.: ' + idOS + '</p>';
		canvas += '<fieldset>';
		canvas += '<input type="password" id="pass" size=30 placeholder="Ingrese su contraseña" ';
		canvas += 'class="text ui-widget-content ui-corner-all"/>';
		canvas += '<h5 id="msg"></h5>';
		canvas += '</fieldset>';
		canvas += '</div>';

		$(canvas).dialog({
			title: 'Anular Orden de Servicio',
			modal: true,
			resizable: false,
			draggable: false,
			close: function () {
				$(this).dialog("destroy");
			},
			buttons: {
				Anular: function () {
					pass = $(this).find('#pass').val();
					if (pass !== "") {
						pass = hex_md5(pass);
						$('#pass').val('');
						$(this).dialog('destroy');
						var $aux = $('#loading').dialog({
							title: 'Anulando Orden de Servicio',
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

						$.post(baseURL + api + isoPais + '/consulta/anularos', {
								'data-idOS': idOS,
								'data-pass': pass,
								ceo_name: ceo_cook
							})
							.done(function (data) {
								$aux.dialog('destroy');
								if (!data.ERROR) {
									var message = 'La OS ha sido anulada exitosamente.';
									message += aplicaCostD === 'D' ? ' La tarifa por <b>Servicios operativos y de logística</b>, será incluida en la siguiente orden' : '';
									notificacion("Orden de Servicio anulada", message);
									COS_var.tablaOS.fnDeleteRow(COS_var.tablaOS.fnGetPosition(btnAnular.parentNode.parentNode));
								} else {
									if (data.ERROR == '-29') {
										alert('Usuario actualmente desconectado');
										location.reload();
									} else {
										notificacion("Anulando Orden de Servicio", data.ERROR);
									}
								}
							});
					} else {
						$(this).find($('#msg')).text('Debe ingresar su contraseña');
					}
				}
			}
		});
	});


	$("#tabla-datos-general").on("click", "#factura", function () {

		dw = $(this).attr("data-dw").toLowerCase();
		orden = $(this).parents("tr").attr("id");

		if (dw) {
			$(this).attr("href", "http://www.plata.com.ve/consulta_factura/lotes.php?lote=" + orden);
			$(this).attr('target', '_blank');
			$(this).submit();
		} else {
			$(this).removeAttr("href");
			$(this).removeAttr('target');
			$aux = $("#loading").dialog({
				title: 'Descargando factura',
				modal: true,
				close: function () {
					$(this).dialog('close')
				},
				resizable: false
			});
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
			$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + orden + '" />');
			$('form#formulario').append($('#data-OS'));
			$('form#formulario').attr('action', baseURL + api + isoPais + "/consulta/facturar");
			$('form#formulario').submit();
			setTimeout(function () {
				$aux.dialog('destroy')
			}, 8000);
		}

	});

	$("#tabla-datos-general").on("click", "#facturaOS", function () { // descargar factura orden de servicio
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var OS = $(this).parents("tr").attr('id');
		$aux = $("#loading").dialog({
			title: 'Descargando archivo Facturacion',
			modal: true,
			close: function () {
				$(this).dialog('close')
			},
			resizable: false
		});
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('form#formulario').append('<input type="hidden" name="data-idOS" value="' + OS + '" />');
		$('form#formulario').append($('#data-OS'));
		$('form#formulario').attr('action', baseURL + api + isoPais + "/consulta/downloadFacturacionOS");
		$('form#formulario').submit();
		setTimeout(function () {
			$aux.dialog('destroy')
		}, 8000);

	});

}); // fin document ready

function notificacion(titu, msg) {
	var canvas = "<div>" + msg + "</div>";
	$(canvas).dialog({
		title: titu,
		modal: true,
		resizable: false,
		draggable: false,
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
		},
		buttons: {
			aceptar: function () {
				$(this).dialog('destroy');
			}
		}
	});
}
