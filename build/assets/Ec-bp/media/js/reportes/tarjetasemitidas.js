var colores = ["#54C2D0", "#50C592", "#2B569F", "#994596", "#F5921E", "#298C9A", "#2C855F", "#1A325B", "#522551", "#B46607"];

$(".fecha").keypress(function (e) {
	if (e.keycode != 8 || e.keycode != 46) {
		return false;
	}
});

$(document).ready(function () {

$("#cargando_empresa").fadeIn("fast");

$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function (response) {
	data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
		format: CryptoJSAesJson
	}).toString(CryptoJS.enc.Utf8))
	$("#cargando_empresa").fadeOut("fast");
	if (!(data.ERROR)) {

		$.each(data.lista, function (k, v) {
			$("#repTarjetasEmitidas_empresa").append('<option acrif="' + v.acrif + '" value="' + v.accodcia + '" acnomcia="' + v.acnomcia + '" acrazonsocial="' + v.acrazonsocial + '" acdesc="' + v.acdesc + '" accodcia="' + v.accodcia + '">' + v.acnomcia + '</option>');
		});
	} else {
		if (data.ERROR.indexOf('-29') != -1) {
			alert("Usuario actualmente desconectado");
			$(location).attr('href', baseURL + isoPais + '/login');
		} else {
			$("#repTarjetasEmitidas_empresa").append('<option value="" >' + data.ERROR + '</option>');
		}
	}
})
options = {
	pattern: 'mm/yyyy',
	selectedYear: 2019,
	startYear: 2008,
	finalYear: 2019,
	monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
};

$('.monthpicker').monthpicker(options);
$('.ui-datepicker.ui-widget.ui-widget-content.ui-helper-clearfix.ui-corner-all').addClass('monthpicker-border');

//METODO PARA REALIZAR LA BUSQUEDA
$("#repTarjetasEmitidas_btnBuscar").click(function () {

		var filtro_busq = {};
		var $consulta;
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);

		if (validar_filtro_busqueda("lotes-2")) {
			var form = $('#form-criterio-busqueda');
			validateForms(form);
			if (form.valid()) {
				$('#cargando').fadeIn("slow");
				$(this).hide();
				$('#div_tablaDetalle').fadeOut("fast");

				filtro_busq.empresa = $("#repTarjetasEmitidas_empresa").val();
				filtro_busq.fechaMes = $("#repTarjetasEmitidas_fecha_mes").val();
				filtro_busq.fechaInicial = $("#repTarjetasEmitidas_fecha_in").val();
				filtro_busq.fechaFin = $("#repTarjetasEmitidas_fecha_fin").val();
				if ($("#radio-general").is(":checked")) {
					filtro_busq.radioGeneral = $("#radio-general").val();
				} else {
					filtro_busq.radioGeneral = $("#radio-producto").val();
				}
				filtro_busq.paginaActual = 1;

				filtro_busq.acrif = $("option:selected", "#repTarjetasEmitidas_empresa").attr("acrif");
				filtro_busq.acnomcia = $("option:selected", "#repTarjetasEmitidas_empresa").attr("acnomcia");
				var dataRequest = JSON.stringify(filtro_busq);

				//SE REALIZA LA INVOCACION AJAX
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {
					format: CryptoJSAesJson
				}).toString();
				$consulta = $.post(baseURL + api + isoPais + "/reportes/tarjetasemitidas", {
					request: dataRequest,
					ceo_name: ceo_cook,
					plot: btoa(ceo_cook)
				});
				//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"

				$consulta.done(function (response) {
						data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
							format: CryptoJSAesJson
						}).toString(CryptoJS.enc.Utf8))
						$("#mensaje").remove();
						$("#view-results").attr("style", "");
						$('#cargando').fadeOut("slow");
						$("#repTarjetasEmitidas_btnBuscar").show();
						$("#div_tablaDetalle").fadeIn("slow");
						var contenedor = $("#div_tablaDetalle");
						contenedor.empty();
						var tbody;
						var thead;
						var caption;
						var tr;
						var td;
						var th;
						var div;
						var tabla;
						var a;
						var span;


						div = $(document.createElement("div")).appendTo(contenedor);
						div.attr("id", "top-batchs");
						$(div).append('Tarjetas emitidas');

						if (data.rc == "0") {
							div = $(document.createElement("div")).appendTo(contenedor);
							div.attr("id", "view-results");

							a = $(document.createElement("a")).appendTo(div);
							span = $(a).append("<span title='Exportar Excel' data-icon ='&#xe05a;' aria-hidden = 'true' class = 'icon'></span>");
							span.attr("aria-hidden", "true");
							span.attr("class", "icon");
							span.attr("data-icon", '&#xe050;');
							span.click(function () {
								/*datos = {
									idEmpresa: filtro_busq.acrif,
									nomEmpresa: filtro_busq.acnomcia,
									empresa: filtro_busq.empresa,
									fechaInicial: filtro_busq.fechaInicial,
									fechaFin: filtro_busq.fechaFin,
									radioGeneral: filtro_busq.radioGeneral
								}

								descargarArchivo(datos, baseURL+api+isoPais+"/reportes/tarjetasEmitidasExpXLS", "Exportar Excel" );*/
								var ceo_cook = decodeURIComponent(
									document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
								);
								$('form#formulario').empty();
								$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
								$('form#formulario').append('<input type="hidden" name="idEmpresa" value="' + filtro_busq.acrif + '" />');
								$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="' + filtro_busq.acnomcia + '" />');
								$('form#formulario').append('<input type="hidden" name="empresa" value="' + filtro_busq.empresa + '" />');
								$('form#formulario').append('<input type="hidden" name="fechaMes" value="' + filtro_busq.fechaMes + '" />');
								$('form#formulario').append('<input type="hidden" name="fechaInicial" value="' + filtro_busq.fechaInicial + '" />');
								$('form#formulario').append('<input type="hidden" name="fechaFin" value="' + filtro_busq.fechaFin + '" />');
								$('form#formulario').append('<input type="hidden" name="radioGeneral" value="' + filtro_busq.radioGeneral + '" />');
								$('form#formulario').attr('action', baseURL + api + isoPais + "/reportes/tarjetasEmitidasExpXLS");
								$('form#formulario').submit();
							});

							a = $(document.createElement("a")).appendTo(div);
							span = $(a).append("<span title='Ver gráfico' data-icon ='&#xe050' aria-hidden = 'true' class = 'icon'></span>");
							span.attr("aria-hidden", "true");
							span.attr("class", "icon");
							span.attr("data-icon", '&#xe050;');
							span.click(function () {
								/*datos = {
										idEmpresa: filtro_busq.acrif,
										nomEmpresa: filtro_busq.acnomcia,
										empresa: filtro_busq.empresa,
										fechaInicial: filtro_busq.fechaInicial,
										fechaFin: filtro_busq.fechaFin,
										radioGeneral: filtro_busq.radioGeneral
									}
								descargarArchivo(datos, baseURL+api+isoPais+"/reportes/tarjetasEmitidasExpPDF", "Exportar PDF" );*/
								var ceo_cook = decodeURIComponent(
									document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
								);
								$('form#formulario').empty();
								$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
								$('form#formulario').append('<input type="hidden" name="idEmpresa" value="' + filtro_busq.acrif + '" />');
								$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="' + filtro_busq.acnomcia + '" />');
								$('form#formulario').append('<input type="hidden" name="empresa" value="' + filtro_busq.empresa + '" />');
								$('form#formulario').append('<input type="hidden" name="fechaInicial" value="' + filtro_busq.fechaInicial + '" />');
								$('form#formulario').append('<input type="hidden" name="fechaFin" value="' + filtro_busq.fechaFin + '" />');
								$('form#formulario').append('<input type="hidden" name="radioGeneral" value="' + filtro_busq.radioGeneral + '" />');
								$('form#formulario').attr('action', baseURL + api + isoPais + "/reportes/tarjetasEmitidasExpPDF");
								$('form#formulario').submit();
							});

							if ($('#radio-general').is(":checked")) {

								a = $(document.createElement("a")).appendTo(div);
								span = $(a).append("<span title='Ver gráfico' data-icon ='&#xe050' aria-hidden = 'true' class = 'icon'></span>");
								span.attr("aria-hidden", "true");
								span.attr("class", "icon");
								span.attr("data-icon", '&#xe050;');
								span.click(function () {

									// GRAFICA
									var aux = {};
									var _axis = "Bolivares";

									var jsonChart = {
										title: {
											text: $("#titulograficotext").attr("data")
										},
										legend: {
											position: "top"
										},
										series: [],
										categoryAxis: {
											categories: [],
											labels: {
												rotation: -45
											}
										},
										valueAxis: {
											name: _axis,
											title: {
												text: ""
											}
										}
									}


									// SE OBTIENE LAS CATEGORIAS
									$.each(data.listaGrafico[0].categorias, function (posLista, itemLista) {
										jsonChart.categoryAxis.categories.push(itemLista.nombreCategoria);
									});

									// SE OBTIENE LAS series

									$.each(data.listaGrafico[0].series, function (posSeries, itemSeries) {
										var serie = {};
										serie.name = itemSeries.nombreSerie;
										serie.data = itemSeries.valores;
										serie.axis = _axis;
										serie.color = colores[posSeries];
										jsonChart.series.push(serie);
									});


									// GRAFICA
									$("#chart").kendoChart(jsonChart);
									$("#chart").dialog({
										modal: true,
										width: 900,
										height: 600
									});
									$("#chart svg").width(Number($(window).width()));
									$("#chart svg").height(Number($(window).height()));
									$("#chart").data("kendoChart").refresh();
								});



								tabla = $(document.createElement("table")).appendTo(contenedor);
								tabla.attr("id", "tabla-datos-general");
								tabla.attr("class", "tabla-reportes");

								thead = $(document.createElement("thead")).appendTo(tabla);
								tbody = $(document.createElement("tbody")).appendTo(tabla);
								tbody.attr("id", "tbody-datos-general");
								tbody.attr("class", "tbody-reportes");
								tr = $(document.createElement("tr")).appendTo(thead);
								tr.attr("id", "datos-principales");
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#producto").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#emision").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#reptarjeta").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#repclave").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#total").attr("data"));


								$.each(data.lista, function (posLista, itemLista) {

									tr = $(document.createElement("tr")).appendTo(tbody);
									td = $(document.createElement("td")).appendTo(tr);
									td.html(itemLista.nomProducto);
									td = $(document.createElement("td")).appendTo(tr);
									td.html(itemLista.totalEmision);
									td.attr("style", "text-align: center")
									td = $(document.createElement("td")).appendTo(tr);
									td.html(itemLista.totalReposicionTarjeta);
									td.attr("style", "text-align: center")
									td = $(document.createElement("td")).appendTo(tr);
									td.html(itemLista.totalReposicionClave);
									td.attr("style", "text-align: center")
									td = $(document.createElement("td")).appendTo(tr);
									td.html(itemLista.totalProducto);
									td.attr("style", "text-align: center");

								});

								$('#tabla-datos-general tbody tr:even').addClass('even ');


							} else {

								$.each(data.lista, function (posLista, itemLista) {

									div = $(document.createElement("div")).appendTo(contenedor);
									div.attr("id", "view-results");

									span.click(function () {

										var $consulta;


										var aux = {};
										var _axis = "Bolivares";

										var jsonChart = {
											title: {
												text: ""
											},
											legend: {
												position: "top"
											},
											series: [],
											categoryAxis: {
												categories: [$("#categoria_uno").attr("data"), $("#categoria_dos").attr("data")]
											},
											valueAxis: {
												name: _axis,
												title: {
													text: ""
												}
											}

										}


										// SE OBTIENE LAS CATEGORIAS


										// SE OBTIENE LAS SERIES
										var serie = {};
										var seriep = {};
										var seriea = {};
										var titulo = {};

										jsonChart.title.text = itemLista.nomProducto;

										serie.name = $("#emision").attr("data");
										serie.data = [itemLista.emision, itemLista.emisionSuplementaria.totalEmision];
										serie.axis = _axis;
										serie.color = colores[0];

										seriep.name = $("#reptarjeta").attr("data");
										seriep.data = [itemLista.repPlastico, itemLista.emisionSuplementaria.totalReposicionTarjeta];
										seriep.axis = _axis;
										seriep.color = colores[1];

										seriea.name = $("#repclave").attr("data");
										seriea.data = [itemLista.repClave, itemLista.emisionSuplementaria.totalReposicionClave];
										seriea.axis = _axis;
										seriea.color = colores[2];

										jsonChart.series.push(seriep);
										jsonChart.series.push(seriea);
										jsonChart.series.push(serie);

										// GRAFICA
										$("#chart").kendoChart(jsonChart);
										$("#chart").dialog({
											modal: true,
											width: 900,
											height: 600
										});
										$("#chart svg").width(Number($(window).width()));
										$("#chart svg").height(Number($(window).height()));
										$("#chart").data("kendoChart").refresh();
									});


								tabla = $(document.createElement("table")).appendTo(contenedor);
								tabla.attr("class", "tabla-reportes");
								tabla.attr("id", "tabla-datos-general");

								thead = $(document.createElement("thead")).appendTo(tabla);
								thead.attr("id", "thead-datos-principales");
								tbody = $(document.createElement("tbody")).appendTo(tabla);
								tbody.attr("class", "tbody-reportes");

								tr = $(document.createElement("tr")).appendTo(thead);
								tr.attr("id", "datos-principales");
								th = $(document.createElement("th")).appendTo(tr);
								th.html(itemLista.nomProducto);
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#emision").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#reptarjeta").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#repclave").attr("data"));
								th = $(document.createElement("th")).appendTo(tr);
								th.html($("#total").attr("data"));

								tr = $(document.createElement("tr")).appendTo(tbody);
								td = $(document.createElement("td")).appendTo(tr);
								td.html($("#categoria_uno").attr("data"));
								td = $(document.createElement("td")).appendTo(tr);
								td = $(document.createElement("a")).appendTo(td);
								td.attr("class", "emision");
								td.attr("id", posLista);
								td.html(itemLista.totalEmision);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td = $(document.createElement("a")).appendTo(td);
								td.attr("class", "reposicion");
								td.attr("id", posLista);
								td.html(itemLista.totalReposicionTarjeta);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalReposicionClave);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalProducto);
								td.attr("style", "text-align: center");

								tr = $(document.createElement("tr")).appendTo(tbody);
								td = $(document.createElement("td")).appendTo(tr);
								td.html($("#categoria_dos").attr("data"));
								td = $(document.createElement("td")).appendTo(tr);
								td = $(document.createElement("a")).appendTo(td);
								td.attr("id", posLista);
								td.attr("class", "suplementario_emision");
								td.html(itemLista.emisionSuplementaria.totalEmision);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td = $(document.createElement("a")).appendTo(td);
								td.attr("id", posLista);
								td.attr("class", "suplementario_reposicion");
								td.html(itemLista.emisionSuplementaria.totalReposicionTarjeta);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.emisionSuplementaria.totalReposicionClave);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.emisionSuplementaria.totalProducto);
								td.attr("style", "text-align: center");

								tr = $(document.createElement("tr")).appendTo(tbody);
								td = $(document.createElement("td")).appendTo(tr);
								td.html($("#total").attr("data"));
								td.attr("style", "text-align: right")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalEmision);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalReposicionTarjeta);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalReposicionClave);
								td.attr("style", "text-align: center")
								td = $(document.createElement("td")).appendTo(tr);
								td.html(itemLista.totalProducto)
								td.attr("style", "text-align: center; ");

							});

						$('.tabla-reportes tbody tr:even').addClass('even ');

					}
					$("tbody.tbody-reportes").on('click', 'tr td', function (event) {
						//console.log($(this).closest('table').find('th')[0].innerText)
						$("#chart").children().remove();
						var selectClass = $(event.target).attr('class');
						var selectId = event.target.id;
						var nombreProducto = $(this).closest('table').find('th')[0].innerText;
						console.log(data.lista[selectId].detalleEmisiones)
						console.log(data.lista[selectId])
						var dataGeneral = data.lista;
						propiedadTrabajar = cellElement.class == 'emision' ? 'detalleEmisiones' : 'detallesReposiciones';
						if(nombreProducto == dataGeneral.nomProducto && selectClass == ){

						}

						// //$.each(data.lista, function (posLista, itemLista) {
						// 	 var dataTable = data.lista.detalleEmisiones[selectId];
						// 	// console.log(dataTable);

						// 		//$.each(dataTable, function (key, detalLista){
						// 			// console.log(detalLista)
						// 				var contenidoTabla = $("#chart");
						// 				$("#chart").dialog({ modal: true, title: "Consulta tarjetas emitidas", width: 960, height: "auto", draggable: false });
						// 				var newTabla =$(document.createElement("table")).appendTo(contenidoTabla);
						// 				newTabla.attr("class", "tabla-detalles");
						// 				newTabla.attr("id", "tabla-detalles-general");

						// 				thead = $(document.createElement("thead")).appendTo(newTabla);
						// 				thead.attr("id", "thead-detalles-principales");
						// 				thead.attr("class", "thead-detalles-principales");
						// 				tbody = $(document.createElement("tbody")).appendTo(newTabla);
						// 				tbody.attr("class", "tbody-detalles");

						// 				tr = $(document.createElement("tr")).appendTo(thead);
						// 				tr.attr("class", "datos-detalles");
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#fecha_emision").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#numero_lote").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#numero_tarjeta").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#cedula").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#nombre").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#apellido").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#ubicación").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#estado_emision").attr("data"));
						// 				th = $(document.createElement("th")).appendTo(tr);
						// 				th.html($("#estado_plastico").attr("data"));

						// 			$.each(dataTable, function (i, tabla){
						// 				//console.log(tabla);

						// 					tr = $(document.createElement("tr")).appendTo(tbody);
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.fechaEmision);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.nroLote);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.nroTarjeta);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.cedula);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.nombres);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.apellidos);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.ubicacion);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.estadoEmision);
						// 					td.attr("class", "info-detal");
						// 					td = $(document.createElement("td")).appendTo(tr);
						// 					td.html(tabla.estadoPlastico);
						// 					td.attr("class", "info-detal");
						// 			})
							//	})
						//});

						// switch (selectClass) {
						// 	case 'emision':
						// 		console.log('se elegio' + selectClass);
						// 		break;
						// 	case 'reposicion':
						// 		console.log('se elegio' + selectClass);
						// 		break;
						// 	case 'suplementario_emision':
						// 		console.log('se elegio' + selectClass);
						// 		break;
						// 	case 'suplementario_reposicion':
						// 		console.log('se elegio' + selectClass);
						// 		break;
						// 	default:
						// 		console.log('no se elegio nada')
						// 		break;
						// }
					});


				} else {
					if (data.rc == "-29") {
						alert("Usuario actualmente desconectado");
						$(location).attr('href', baseURL + isoPais + '/login');
					} else if (data) {
						var contenedor = $("#div_tablaDetalle");
						$("#tabla-datos-general").fadeOut("fast");
						$("#view-results").attr("style", "display:none");
						var div = $(document.createElement("div")).appendTo(contenedor);
						div.attr("id", "mensaje");
						div.attr("style", "background-color:rgb(252,199,199); margin-top:60px;");
						var p = $(document.createElement("p")).appendTo(div);
						if (data.rc == "-150")
							p.html(data.mensaje);
						else
							p.html(data);
						p.attr("style", "text-align:center;width:638px;padding:10px;font-size:14px");
					} else {
						$("#mensaje").remove();
						var contenedor = $("#div_tablaDetalle");
						$("#tabla-datos-general").fadeOut("fast");
						$("#view-results").attr("style", "display:none");
						var div = $(document.createElement("div")).appendTo(contenedor);
						div.attr("id", "mensaje");
						div.attr("style", "background-color:rgb(252,199,199); margin-top:60px;");
						var p = $(document.createElement("p")).appendTo(div);
						if (data.rc == "-150")
							p.html(data.mensaje);
						else
							p.html(data);
						p.attr("style", "text-align:center;width:638px;padding:10px;font-size:14px");
					}
				}

			});
		} else {
			showErrMsg('Verifiqua los datos ingresados e intenta nuevamente.');
		}
		};
	});

	});









function validar_filtro_busqueda(div) {
	var valido = true;
	//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
	$.each($("#" + div + " input[type='text'].required"), function (posItem, item) {
		var $elem = $(item);
		if ($elem.val() == "") {
			valido = false;
			$elem.attr("style", "border-color:red");
		} else {
			$elem.attr("style", "");
		}
	});

	//VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
	$.each($("#" + div + " select.required"), function (posItem, item) {
		var $elem = $(item);
		if ($elem.val() == "") {
			valido = false;
			$elem.attr("style", "border-color:red");
		} else {
			$elem.attr("style", "");
		}
	});


	//VALIDA INPUT:CHECKBOX  y INPUT:RADIO QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
	var check = $("#" + div + " input[type='checkbox'].required:checked").length;
	var radio = $("#" + div + " input[type='radio'].required:checked ").length;
	if ((check == "") && ($("#" + div + " input[type='checkbox'].required").length != "")) {
		valido = false;
		$("#" + div + " input[type='checkbox'].required").next().attr("style", "color:red");
	} else {
		$("#" + div + " input[type='checkbox'].required").next().attr("style", "");
	}

	if ((radio == "") && ($("#" + div + " input[type='radio'].required").length != "")) {
		valido = false;
		$("#" + div + " input[type='radio'].required").next().attr("style", "color:red");
	} else {
		$("#" + div + " input[type='radio'].required").next().attr("style", "");
	}


	if (!valido) {
		$(".div_tabla_detalle").fadeOut("fast");
		showErrMsg();
	} else {
		$("#mensajeError").fadeOut("fast");
	}


	return valido;
}



function descargarArchivo(datos, url, titulo) {

	$aux = $("#cargando").dialog({
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
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '">');
			$('form#formulario').append('<input type="hidden" name="bytes" value="' + JSON.stringify(data.bytes) + '" />');
			$('form#formulario').append('<input type="hidden" name="ext" value="' + data.ext + '" />');
			$('form#formulario').append('<input type="hidden" name="nombreArchivo" value="' + data.nombreArchivo + '" />');
			$('form#formulario').attr('action', baseURL + isoPais + "/file");
			$('form#formulario').submit()
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
					$(this).dialog("destroy");
				}
			}
		}
	});
}
