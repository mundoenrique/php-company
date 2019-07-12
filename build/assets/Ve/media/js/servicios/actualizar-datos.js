$(function () { // Document ready

	$("#archivo").on('click', function () {
		$("#userfile").trigger('click');
	});


	$("#userfile").on("click", function () {

		$(this).fileupload({
			type: 'post',
			replaceFileInput: false,
			url: baseURL + api + isoPais + "/servicios/actualizar-datos/cargarArchivo",

			add: function (e, data) {
				f = $('#userfile').val();
				$('#archivo').val($('#userfile').val());
				dat = data;
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
					);
				dat.ceo_name = ceo_cook
				var ext = $('#userfile').val().substr($('#userfile').val().lastIndexOf(".") + 1).toLowerCase();
				if (ext === "xls" || ext === "xlsx") {
					data.context = $('#cargarXLS').click(function () {

						$("#cargarXLS").replaceWith('<h3 id="cargando_archivo">Cargando...</h3>');
						// dat.formData = {'data-rif':$("option:selected","#listaEmpresasSuc").attr("data-rif")};
						dat.submit().success(function (result, textStatus, jqXHR) {
							result = $.parseJSON(result);
							if (result) {
								if (!result.ERROR) {
									mostrarError(result);
								} else {
									if (result.rc == '-61') {
										alert('Usuario actualmente desconectado');
										location.reload();
									} else {
										notificacion("Cargar archivo: actualizar datos", result.ERROR);
									}
								}
							}

							$('#userfile').val("");
							$('#archivo').val("");
						});

					});
				} else {
					notificacion("Cargar archivo: actualizar datos", "Tipo de archivo no permitido. <h5>Formato requerido: excel (.xls ó .xlsx)</h5>");
					$('#userfile').val("");
					$('#archivo').val("");
				}
			},
			done: function (e, data) {

				$('#userfile').val("");
				$('#archivo').val("");
				$('#cargando_archivo').replaceWith('<button id="cargarXLS" >Cargar archivo</button>');
			},
			error: function (e) {
				notificacion("Cargar archivo: actualizar datos", "Error al intentar cargar el archivo");
				$('#userfile').val("");
				$('#archivo').val("");
				$('#cargando_archivo').replaceWith('<button id="cargarXLS" >Cargar archivo</button>');
			}
		});
	});


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
			$("#estatus").val("2");
			$("#buscar-datos").click();
		}

	}


	$("#buscar-datos").on("click", function () {

		$("#buscar-datos").hide();
		$("#loading").dialog({
			title: "Buscando datos",
			modal: true
		});
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var estatus = $("#estatus").val();
		var descargable = $('option:selected', $("#estatus")).attr('descargable');

		$.post(baseURL + api + isoPais + '/servicios/actualizar-datos/buscar-datos', {
				"data-nombre": $('#nombre').val(),
				"data-status": estatus,
				ceo_name: ceo_cook
			})
			.done(function (data) {
				$("#loading").dialog("destroy");
				$("#buscar-datos").show();
				if (!data.ERROR) {
					$('#resultado-busqueda').removeClass('elem-hidden');

					if ($("#tabla-act-datos").hasClass('dataTable')) {
						$('#tabla-act-datos').dataTable().fnClearTable();
						$('#tabla-act-datos').dataTable().fnDestroy();
					}
					$('#tabla-act-datos tbody').empty();

					if ($(".op-AD").length == 0 && descargable) {
						$("#datos-principales").append("<th class='op-AD td-corto'>Opción</th>");
					} else if (!descargable) {
						$(".op-AD").remove();
					}


					$.each(data.lista, function (k, v) {
						var d = v.fechaRegistro;
						d = $.datepicker.formatDate('dd/mm/yy', new Date(d.substr(0, 4) + '/' + d.substr(4, 2) + '/' + d.substr(6, 7)));
						fila = "<tr nomb='" + v.nombreArchivo + "' fecha='" + v.fechaRegistro + "' >	<td >" + v.nombreArchivo + "</td><td class='td-medio'>" + v.idLote + "</td><td >" + v.nombreStatus + "</td><td class='td-medio'>" + d + "</td><td id='td-nombre-2'>" + v.obs + "</td>";
						if (descargable) {
							$(".op-AD").show();
							$('.ampliar').removeAttr('id');
							fila += "<td class='op-AD td-corto'><a id='downXLS'><span aria-hidden='true' class='icon' data-icon=&#xe05a; title='Descargar'></span></a></td></tr>";


						} else {
							$(".op-AD").hide();
							fila += "</tr>"
							$('.ampliar').attr('id', 'td-nombre-2');
						}

						$('#tabla-act-datos tbody').append(fila);

					});

					dataTable();

				} else {
					if (data.ERROR == "-29") {
						alert('Usuario actualmente desconectado');
						location.reload();
					} else {
						notificacion('Buscar datos', data.ERROR);
					}
				}
			});

	});


	$('#tabla-act-datos').on('click', '#downXLS', function () {
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);

		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$('form#formulario').append('<input type="hidden" name="data-fecha" value="' + $(this).parents("tr").attr('fecha') + '" />');
		$('form#formulario').append('<input type="hidden" name="data-nomb" value="' + $(this).parents("tr").attr('nomb') + '" />');
		$('form#formulario').attr('action', baseURL + api + isoPais + "/servicios/actualizar-datos/downXLS");
		$('form#formulario').submit()
	});


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



	function dataTable() {


		$("#tabla-act-datos").dataTable({
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
	}


}); //--Fin document ready :)
