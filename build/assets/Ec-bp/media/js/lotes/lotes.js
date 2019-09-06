$(function () { // Document ready

	var f, dir, forma;
	var ceo_cook;
	$('#lotes-2').show();
	$(".aviso").removeClass("elem-hidden");
	actualizarLote();

	// Cargar archivo

	var dat;

	$('#archivo').on('click', function () {
		$("#userfile").trigger('click');
	});

	$('#userfile').fileupload({
		type: 'post',
		replaceFileInput: false,
		// formData: {'data-tipoLote':tipol},
		url: baseURL + isoPais + "/lotes/upload",

		add: function (e, data) {
			f = $('#userfile').val();
			$('#archivo').val($('#userfile').val());
			dat = data;
			var ext = $('#userfile').val().substr($('#userfile').val().lastIndexOf(".") + 1).toLowerCase();
			if (ext == "txt" || ext == "xls" || ext == "xlsx") {
				data.context = $('#cargaLote')
					.click(function () {

						if ($("#tipoLote").val() != "") {
							$("#cargaLote").replaceWith('<h3 id="cargando">Cargando...</h3>');
							ceo_cook = decodeURIComponent(
								document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
							);
							var paquete = {
								data_tipoLote: $("#tipoLote").val(),
								data_formatolote: $("#tipoLote option:selected").attr('rel')
							};
							var dataRequest = JSON.stringify(paquete)
							dataRequest  = CryptoJS.AES.encrypt(dataRequest , ceo_cook, {format: CryptoJSAesJson}).toString();
							dat.formData = {
								request: dataRequest,
								ceo_name: ceo_cook,
								plot: btoa(ceo_cook)
							}
							dat.submit().done(function (response, textStatus, jqXHR) {
								result = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
								if (result) {

									if (!result.ERROR) {
										mostrarError(result);
									} else {
										if (result.ERROR == '-29') {
											alert('Usuario actualmente desconectado');
											location.reload();
										} else {

											notificacion("Cargando archivo", result.ERROR);
										}
									}
								}


								$('#userfile').val("");
								$('#archivo').val("");
							});
						} else {
							notificacion("Cargando archivo", "Selecciona un tipo de lote");
						}
					});
			} else {
				notificacion("Cargando archivo", "Tipo de archivo no permitido. <h5>Formato requerido: xls</h5>");
				$('#userfile').val("");
				$('#archivo').val("");
			}
		},
		done: function (e, data) {

			$('#userfile').val("");
			$('#archivo').val("");
			$('#cargando').replaceWith('<button id="cargaLote" >' + $('#boton').val() + '</button>');
		},
		error: function (e) {
			notificacion("Cargando archivo", "error al intentar cargar el archivo");
			$('#userfile').val("");
			$('#archivo').val("");
			$('#cargando').replaceWith('<button id="cargaLote" >' + $('#boton').val() + '</button>');
		}
	});

	//-- Fin cargar archivo

	function mostrarError(result) {

		if (result.rc != "0") {

			var canvas = "<h4>ENCABEZADO</h4>";
			$.each(result.erroresFormato.erroresEncabezado.errores, function (k, v) {
				canvas += "<h6>" + v + "</h6>";
			});

			canvas += "<h4>REGISTRO</h4>";
			$.each(result.erroresFormato.erroresRegistros, function (k, vv) {
				canvas += "<h5>" + vv.nombre + "</h5>";
				$.each(result.erroresFormato.erroresRegistros[k].errores, function (i, v) {
					canvas += "<h6>" + v + "<h6/>";
				});

			});
			notificacion(result.msg, canvas);

		} else {
			notificacion("Cargando archivo", "Archivo cargado con éxito.\n" + result.msg);
			actualizarLote();
		}

	}

	// Refrescar lote cada 10 segundos

	self.setInterval(function () {
		actualizarLote()
	}, 10000);
	var datatable;

	function actualizarLote() {

		//$("#table-text-lotes tbody").append("<h3 id='actualizador'>Cargando...</h3>");
		if (!$("#table-text-lotes").hasClass('dataTable')) {
			$('#actualizador').show();
		}
		$.get(baseURL + api + isoPais + "/lotes/lista/pendientes",
			function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
					format: CryptoJSAesJson
				}).toString(CryptoJS.enc.Utf8))

				var icon, batch, color, title;

				if (!data.result.ERROR) {

					if ($("#table-text-lotes").hasClass('dataTable')) {
						$('#table-text-lotes').dataTable().fnClearTable();
						$('#table-text-lotes').dataTable().fnDestroy();
					}

					$("#table-text-lotes tbody").empty();
					$('thead').show();
					forma = 1;

					$.inArray('tebcon', data.funciones) != -1 ? confirma = "" : confirma = 'hidden';
					$.inArray('tebelc', data.funciones) != -1 ? elimina = "" : elimina = 'hidden';
					var anchor;

					$.each(data.result.lista, function (k, v) {
						if (v.estatus == 5) { //"con error";
							icon = "&#xe003;";
							color = "icon-batchs-red";
							dir = "detalle";
							title = "Ver lote";
							anchor = "<span aria-hidden='true' class='icon' data-icon=" + icon + "></span>";
						} else if (v.estatus == 1) { //"ok";
							icon = "&#xe083;";
							color = "icon-batchs-green";
							dir = "confirmacion";
							title = "Confirmar lote";
							anchor = "<span aria-hidden='true' class='icon' style='font-size:12px'>Confirmar</span>";
						} else if (v.estatus == 0) { //verificando";
							icon = "&#xe00a;";
							color = "icon-batchs-orange";
							title = "Validando lote";
							anchor = "<span aria-hidden='true' class='icon' data-icon=" + icon + "></span>";
						} else if (v.estatus == 6) { //ok pero con errores
							icon = "&#xe083;";
							color = "icon-batchs-purple";
							title = "Confirmar lote";
							anchor = "<span aria-hidden='true' class='icon' style='font-size:12px'>Confirmar</span>";
						}

						(v.numLote === "") ? v.numLote = '-': v.numLote;
						(v.nombre === "") ? v.nombre = '-': v.nombre;

						batch = "<tr>";
						batch+=   "<td id='icon-batchs' class=" + color + "></td>";
						batch+=   "<td>" + v.numLote + "</td>";
						batch+=   "<td id='td-nombre'>" + v.nombreArchivo + "</td>";
						batch+=   "<td class='field-date'>" + v.fechaCarga + "</td>";
						batch+=   "<td>" + v.descripcion + "</td>";
						batch+=   "<td id='icons-options'>";
						batch+=     "<a " + elimina + " id='borrar' title='Eliminar Lote' data-idTicket=" + v.idTicket;
						batch+=     " data-idLote='" + v.idLote + "' data-arch='" + v.nombreArchivo + "'>";
						batch+=     "<span aria-hidden='true' class='icon' data-icon='&#xe067;'></span></a>";
						batch+=     v.estatus == 6 ? "<a " + confirma + " class='detalle' title='Ver lote' data-idTicket=" + v.idTicket + " data-edo=" + v.estatus + " data-forma=" + forma + " data-opc='verLote'><span aria-hidden='true' class='icon' data-icon='&#xe003;'></span></a>" : "";
						batch += "<a " + confirma + " class='detalle' title='" + title + "' data-idTicket=" + v.idTicket + " data-edo=" + v.estatus + " data-forma=" + forma + ">"+anchor+"</a></td></tr>";

						$("#actualizador").hide();
						$("#table-text-lotes tbody").append(batch);

						forma += 1;
					});

					$('#table-text-lotes').dataTable({
						"iDisplayLength": 10,
						'bDestroy': true,
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

				} else {
					if (data.result.ERROR == '-29') {
						alert('Usuario actualmente desconectado');
						location.reload();
					}

					if (forma > 1) {
						$('#table-text-lotes').dataTable().fnClearTable();
						$('#table-text-lotes').dataTable().fnDestroy();
					}

					$('thead').hide();
					$("#actualizador").hide();
					$("#table-text-lotes tbody").html("<h2 style='text-align:center'>" + data.result.ERROR + "</h2>");


				}


			});

	} //Fin function refrescar()


	//--Fin refrescar lotes


	// Borrar Lote
	$("#table-text-lotes").on("click", "#borrar",
		function () {
			var ticket = $(this).attr("data-idTicket");
			var lote = $(this).attr("data-idLote");
			var arch = $(this).attr("data-arch");

			confirmar($(this).parents('tr'), ticket, lote, arch, "Eliminar Lote");

		}
	);

	//Confirmar borrado lote

	function confirmar($item, ticket, lote, arch, titu) {


		var canvas = "<div id='dialog-confirm'>";
		canvas += "<p>Nombre: " + arch + "</p>  <p><strong>Ingresa tu contraseña</strong></p>";
		canvas += "<form onsubmit='return false'><fieldset><input type='password' id='pass' name='user-password' size=27 placeholder='Ingresa tu contraseña' class='text ui-widget-content ui-corner-all'/>";
		canvas += "<h5 id='msg'></h5></fieldset></form></div>";

		var pass;

		$(canvas).dialog({
			dialogClass: "hide-close",
			title: titu,
			modal: true,
			resizable: false,
			close: function () {
				$(this).dialog("destroy");

			},
			buttons: {
				"Cancelar": {
					text: 'Cancelar',
					class: 'novo-btn-secondary-modal',
					click: function () {
					$(this).dialog("close");
					}
				},
				"Eliminar": {
					text: 'Eliminar',
					class: 'novo-btn-primary-modal',
					click: function () {
						pass = $(this).find('#pass').val();
						if (pass !== "") {
							var form = $(this).find('form');
							validateForms(form);
							if (form.valid()) {
								pass = hex_md5(pass);
								$('#pass').val('');
								$(this).dialog('destroy');
								var $aux = $('#loading').dialog({

									dialogClass: "hide-close",
									title: "Eliminando lote",
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
									}
								});
								ceo_cook = decodeURIComponent(
									document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
								);
								var dataRequest = JSON.stringify ({
									data_idTicket: ticket,
									data_idLote: lote,
									data_pass: pass,
								})
								dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
								$.post(baseURL + api + isoPais + "/lotes/eliminar",  {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)}).done(
									function (response) {
										data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
										$aux.dialog('destroy');

										if (!data.ERROR) {
											notificacion("Eliminando lote", 'Eliminación exitosa');

											$item.fadeOut("slow");
											actualizarLote();
										} else {
											if (data.ERROR == '-29') {
												alert('Usuario actualmente desconectado');
												location.reload();
											} else {
												notificacion("Eliminando lote", data.ERROR);
											}

										}

									});
							} else {
								$(this).find($('#msg')).text('Contraseña inválida');
							}
						} else {
							$(this).find($('#msg')).text('Debes ingresar tu contraseña');
						}

					}
				},
			}
		});

	}
	//--Fin Confirmar borrado lote

	//Fin borrar lote


	// Ver Lote
	$("#table-text-lotes").on("click", ".detalle",
		function () {
			var estado = $(this).attr("data-edo");
			var ticket = $(this).attr("data-idTicket");
			var opc = $(this).attr("data-opc");
			ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			if (estado == "1" || (estado == "6" && !opc)) {
				$("form#confirmar").append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '" />');
				$("form#confirmar").append('<input type="hidden" name="data-estado" value="' + estado + '" />');
				$("form#confirmar").append('<input type="hidden" name="data-idTicket" value="' + ticket + '" />');
				$("form#confirmar").submit();
			} else if (estado == "5" || estado == "6") {
				$("form#detalle").append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '" />');
				$("form#detalle").append('<input type="hidden" name="data-estado" value="' + estado + '" />');
				$("form#detalle").append('<input type="hidden" name="data-idTicket" value="' + ticket + '" />');
				$("form#detalle").submit();
			}

		});

	//Fin Ver lote



	//POUP Notificacion

	function notificacion(titulo, mensaje) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({

			dialogClass: "hide-close",
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			resizable: false,
			close: function () {
				$(this).dialog("destroy");
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

	//--Fin POUP Notificacion
}); //--Fin document ready :)
