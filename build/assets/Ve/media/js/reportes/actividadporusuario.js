$(function () {

	var params = {};

	$("#cargando_empresa").fadeIn("slow");
	$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function (response) {
		var data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
		$("#cargando_empresa").fadeOut("slow");
		if (!data.ERROR) {
			$.each(data.lista, function (k, v) {
				$("#empresa").append('<option value="' + v.accodcia + '" acrif="' + v.acrif + '">' + v.acnomcia + '</option>');
			});
		} else {
			if (data.ERROR == '-29') {
				alert('Usuario actualmente desconectado');
				location.reload();
			} else {
				$("#empresa").append('<option value="" >' + data.ERROR + '</option>');
			}
		}

	});

	selIn = false;
	selFi = false;

	$("#fecha_ini").datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		numberOfMonths: 1,
		maxDate: "+0D",
		onClose: function (selectedDate, inst) {

			if (selIn) {

				sumaMes = new Date($('#fecha_ini').datepicker('getDate').getTime() + 30 * 24 * 60 * 60 * 1000);

				new Date < sumaMes ? sumaMes = new Date : sumaMes = sumaMes;
				selIn = false;
			} else {
				$('#fecha_ini').val("");
				selectedDate = "";
				sumaMes = "+0D";
			}

			$("#fecha_fin").datepicker('option', 'minDate', selectedDate);
			$("#fecha_fin").datepicker('option', 'maxDate', sumaMes);
		},
		onSelect: function () {
			selIn = true;
		}
	});

	$("#fecha_fin").datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		numberOfMonths: 1,
		maxDate: "+0D",
		onClose: function (selectedDate) {

			if (selFi) {
				restaMes = new Date($('#fecha_fin').datepicker('getDate').getTime() - 30 * 24 * 60 * 60 * 1000);
				selFi = false;
			} else {
				$("#fecha_fin").val("");
				selectedDate = "+0D";
				restaMes = "";
			}

			$("#fecha_ini").datepicker('option', 'minDate', restaMes);
			$("#fecha_ini").datepicker('option', 'maxDate', selectedDate);
		},
		onSelect: function () {
			selFi = true;
		}
	});


	$("#empresa").on('change', function () {
		params.acrif = $('option:selected', this).attr('acrif');
	});


	$("#btnBuscar").click(function () {

		if (validar_filtro_busqueda("lotes-2")) {
			// mostrar reporte

			$('#cargando').show();
			$(this).hide();
			$('.resultadosAU').hide();

			params.fecha_ini = $("#fecha_ini").val();
			params.fecha_fin = $("#fecha_fin").val();
			params.acodcia = $("#empresa").val();
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			data_fechaIni=$("#fecha_ini").val(),
			data_fechaFin= $("#fecha_fin").val(),
			data_acodcia=$("#empresa").val()

			var dataRequest = JSON.stringify({
				'data_fechaIni': data_fechaIni,'data_fechaFin':data_fechaFin,'data_acodcia':data_acodcia
			})
			console.log(dataRequest)
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
			$.post(baseURL + api + isoPais + "/reportes/actividadporusuario", {
				request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)
				})
				.always(function (response) {
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
					console.log(data)
					$('#cargando').hide();
					$("#btnBuscar").show();
					$('.resultadosAU').show();
					if (!data.ERROR) {

						if ($("#table-activ-user").hasClass('dataTable')) {
							$('#table-activ-user').dataTable().fnClearTable();
							$('#table-activ-user').dataTable().fnDestroy();
						}
						$('#table-activ-user tbody').empty();
						$('.activities-AU').empty();
						$('.functions-AU').empty();
						$("#view-results").show();
						$('thead').show();


						$thFunciones = "<thead><tr class='OShead-2'><td style='text-align: center'>FUNCIONES</td></tr></thead>";
						$thActividades = "<thead><tr class='OShead-2 OSinfo'><td>Módulo</td><td>Función</td><td>Fecha</td></tr></thead>";
						$verF = "<a class='vF' title='Ver funciones'><span aria-hidden='true' class='icon' data-icon='&#xe003;'></span></a>";
						$verA = "<a class='vA' title='Ver actividades'><span aria-hidden='true' class='icon' data-icon='&#xe003;'></span></a>";

						$tr = "";
						$.each(data.lista, function (k, v) {
							$tr += "<tr id='" + v.userName + "'><td>" + v.userName + "</td>";
							$tr += "<td>" + v.estatus + "</td>";
							$tr += "<td>" + v.fechaUltimaConexion + "</td>";
							$tr += "<td >" + $verA + $verF + "</td>";


							if (data.lista[k].actividades.lista.length == 0) {
								$tr += "<td class='activ A" + v.userName + " elem-hidden'><table class='activities-AU'><tbody>";
								$tr += "<tr class='cell-AU'><td></td><td>Sin actividades</td><td></td></tr>";
							} else {
								$tr += "<td class='activ A" + v.userName + " elem-hidden'><table class='activities-AU'>" + $thActividades + "<tbody>";
								$.each(data.lista[k].actividades.lista, function (key, val) {
									$tr += "<tr class='cell-AU'><td>" + val.modulo + "</td>";
									$tr += "<td>" + val.funcion + "</td>";
									$tr += "<td>" + val.dttimesstamp + "</td></tr>";
								});
							}
							$tr += "</tbody></table></td> </tr>";

							$funcs = "<table class='functions-AU F" + v.userName + "'>" + $thFunciones + "<tbody>";
							$.each(data.lista[k].funciones.lista, function (key, val) {
								$funcs += "<tr><td>" + val.acnomfuncion.toLowerCase().replace(/(^| )(\w)/g, function (x) {
									return x.toUpperCase();
								}) + "</td></tr>";
							})
							if (data.lista[k].funciones.lista.length == 0) {
								$funcs += "<tr><td>Sin funciones</td></tr>";
							}
							$funcs += "</tbody></table>";
							$('#funciones-user').append($funcs);


						});
						$('#table-activ-user tbody').append($tr);


						$.each($('.vF'), function () {
							user = $(this).parents('tr').attr('id');

							$(this).balloon({
								contents: $('.F' + user),
								position: 'right',
								classname: 'tooltip-funcionesAU'
							});

						});

						$('#table-activ-user').dataTable({
							"iDisplayLength": 10,
							'bDestroy': true,
							"sPaginationType": "full_numbers",
							"bLengthChange": false,
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

						$('.dataTable').css('width', '0');

					} else {
						$('thead').hide();
						$('#view-results').hide();
						if (data.ERROR == '-29') {
							alert('Usuario actualmente desconectado');
							location.reload();
						} else {
							$(".dataTables_filter").hide();
							$(".dataTables_info").hide();
							$(".dataTables_paginate").hide();
							$('tbody').html("<h2 style='text-align:center'>" + data.ERROR + "</h2>");
						}
					}

				});

		}
	});


	function validar_filtro_busqueda(div) {

		valido = true;

		//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		$.each($("#" + div + " input[type='text'].required"), function (posItem, item) {

			marcarojo($(item));
		});

		//VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		$.each($("#" + div + " select.required"), function (posItem, item) {
			marcarojo($(item));
		});

		if (!valido) {
			$(".div_tabla_detalle").fadeOut("fast");
			$("#mensajeError").fadeIn("fast");
		} else {
			$("#mensajeError").fadeOut("fast");
		}


		return valido;
	}


	function marcarojo($elem) {
		if ($elem.val() == "") {
			valido = false;
			$elem.attr("style", "border-color:red");
		} else {
			$elem.attr("style", "");
		}
	}


	$('#table-activ-user').on('click', '.vA', function () {
		user = $(this).parents('tr').attr('id');
		$('#table-activ-user .A' + user).is(':visible') ? $('#table-activ-user .A' + user).fadeOut() : $('.A' + user).fadeIn();
		$('#table-activ-user .activ').not('#table-activ-user .A' + user).hide();
	});



	$('#downPDF').on('click', function () {

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/downPDFactividadUsuario");
		$('#data-fechaIni').val(params.fecha_ini);
		$('#data-fechaFin').val(params.fecha_fin);
		$('#data-acodcia').val(params.acodcia);
		$('#data-acrif').val(params.acrif);
		$('#exportTo').submit();

	});

	$('#downXLS').on('click', function () {

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/downXLSactividadUsuario");
		$('#data-fechaIni').val(params.fecha_ini);
		$('#data-fechaFin').val(params.fecha_fin);
		$('#data-acodcia').val(params.acodcia);
		$('#data-acrif').val(params.acrif);
		$('#exportTo').submit();

	});


	function descargarArchivo(datos, url, titulo) {

		$aux = $("#loadImg").dialog({
			title: titulo,
			modal: true,
			close: function () {
				$(this).dialog('close')
			},
			resizable: false
		});
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		datos.ceo_name = ceo_cook;
		$.post(url, datos).done(function (data) {
			$aux.dialog('destroy')
			if (!data.ERROR) {
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				$('#exportTo').empty();
				$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
				$('#exportTo').append('<input type="hidden" name="bytes" value="' + JSON.stringify(data.bytes) + '" />');
				$('#exportTo').append('<input type="hidden" name="ext" value="' + data.ext + '" />');
				$('#exportTo').append('<input type="hidden" name="nombreArchivo" value="' + data.nombreArchivo + '" />');
				$('#exportTo').attr('action', baseURL + isoPais + "/file");
				$('#exportTo').submit()
			} else {
				if (data.ERROR == "-29") {
					alert('Usuario actualmente desconectado');
					location.reload();
				} else {
					notificacion(titulo, data.ERROR)
				}

			}
		})

	}

	function notificacion(titulo, mensaje) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			resizable: false,
			close: function () {
				$(this).dialog("destroy");
			},
			buttons: {
				OK: function () {
					$(this).dialog("destroy");
				}
			}
		});
	}

	//fin de document
});
