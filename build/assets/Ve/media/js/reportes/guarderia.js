var filtro_busq = {};

$(".fecha").keypress(function (e) {
		if (e.keycode != 8 || e.keycode != 46) {
				return false;
		}
});

$(document).ready(function () {
 hide( 'lotes-2' );
 hide( 'batchs-last' );
	var	guarderia_riff = $("#Guarderia-riff").val();
	$.getJSON(baseURL + api + isoPais + '/empresas/consulta-empresa-usuario').always(function( data ) {

		if(!(data.ERROR)){
			$.each( data.lista, function( k, v ){
				if( v.acrif == guarderia_riff ){
					$("#Empresa-nombre").val(v.acnomcia);
					 show( 'lotes-2' );
					  show( 'batchs-last' );
					 hide( 'MensajeLoading' );
				}else{
					;
				}
			});
		}else{
			if(data.ERROR.indexOf('-29') !=-1){
				 alert("Usuario actualmente desconectado");
				 $(location).attr('href',baseURL+isoPais+'/login');
			 }else{
					return false;
			 }
		}
	});

	function show( element ) {
	         var ElementId = document.getElementById( element );
	         if( ElementId ){
	             ElementId.style.display = 'block';
	         }//fin del if
	   }
	    function hide( element ) {
	       var ElementId = document.getElementById( element );
	       if( ElementId ){
	            ElementId.style.display = 'none';
	       }//fin del if
	   }

		$("#export_excel").click(function () {
				descargarArchivo(filtro_busq, baseURL + api + isoPais +
					 "/reportes/guarderiaExpXLS", "Exportar XLS");
		});

		$("#export_pdf").click(function () {
				descargarArchivo(filtro_busq, baseURL + api + isoPais +
					"/reportes/guarderiaExpPDF", "Exportar PDF");
		});

		$("#EstatusLotes-btnBuscar").click(function () {
				evBuscar = true;
				buscarGuarderia("1");
		});

		function buscarGuarderia(paginaActual) {

				var $consulta;
				var nombreEmpresa = $("#Empresa-nombre").val();

				var success = 0;

					filtro_busq.nombreEmpresa = $("#Empresa-nombre").val();
					filtro_busq.paginaActual = paginaActual;
					filtro_busq.isoPais = isoPais;
				  filtro_busq.Fechaini =  $("#Guarderia-fecha-in").val();
				  filtro_busq.Fechafin = $("#Guarderia-fecha-fin").val();


					if( calcularDiffMeses(filtro_busq.Fechaini, filtro_busq.Fechafin) ){
				//	if( CalculateDateDiff(filtro_busq.Fechaini, filtro_busq.Fechafin) ){
						if ( validar_filtro_busqueda("lotes-2") ) {

								$('#cargando').fadeIn("slow");
								$("#EstatusLotes-btnBuscar").hide();
								$('#div_tablaDetalle').fadeOut("fast");

								/******* SE REALIZA LA INVOCACION AJAX *******/
								$consulta = $.post(baseURL + api + isoPais + "/reportes/GuarderiaResult", filtro_busq);
								/******* DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE" *******/
								$consulta.done(function (data) {
										$("#mensaje").remove();
										$('#cargando').fadeOut("slow");
										$("#EstatusLotes-btnBuscar").show();
										$("#div_tablaDetalle").fadeIn("slow");

										var tbody = $("#tbody-datos-general");
										if (evBuscar) {
												tbody.empty();
										}

										contenedor = $("#div_tablaDetalle");
										var tr;
										var td;
										var countRow = data.lista.length;
										/****** DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA... *******/
										/****** DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda" ******/

										if ($(".tbody-statuslotes").hasClass('dataTable')) {
												$('.tbody-statuslotes').dataTable().fnClearTable();
												$('.tbody-statuslotes').dataTable().fnDestroy();
										}

										if (data.rc == "0" && countRow != 0) {

												$("#view-results").attr("style", "display:block");
												$("#tabla-estatus-lotes").fadeIn("fast");
												$("#contend-pagination").show();

												$.each(data.lista, function (posLista, itemLista) {

														tr = $(document.createElement("tr")).appendTo(tbody);
														td = $(document.createElement("td")).appendTo(tr);
														td.html(itemLista.dttimestamp);
														td = $(document.createElement("td")).appendTo(tr);
														td.html(itemLista.numlote);
														td = $(document.createElement("td")).appendTo(tr);
														td.attr("style", "max-width: 180px !important; min-width: 180px !important;");
														td.html(itemLista.beneficiario);
														td = $(document.createElement("td")).appendTo(tr);
														td.html(itemLista.nombre+ " " + itemLista.apellido);
														td = $(document.createElement("td")).appendTo(tr);
														td.html(itemLista.monto_total);
														td = $(document.createElement("td")).appendTo(tr);
														td.html((itemLista.status)?'Aceptado':'Rechazado');

												});

												//paginacion(totalPaginas, 1);

										} else {
												if (data.rc == "-29") {
														alert(data.mensaje);
														$(location).attr('href', baseURL + isoPais + '/login');
												} else {
														$("#mensaje").remove();
														$("#tabla-estatus-lotes").fadeOut("fast");
														$("#view-results").attr("style", "display:none");
														$("#contend-pagination").hide();
														var div = $(document.createElement("div")).appendTo(contenedor);
														div.attr("id", "mensaje");
														div.attr("style", "background-color:rgb(252,199,199); margin-top:45px;");
														var p = $(document.createElement("p")).appendTo(div);
														p.html("No posee registros");
														p.attr("style", "text-align:center;padding:10px;font-size:14px");
												}
										}
								});
						}
					}

		}; //Fin Funcion  buscarStatusTarjetasHambientes

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
						$("#mensajeError").html("Por favor rellene los campos marcados en color rojo");
						$("#mensajeError").fadeIn("fast");
				} else {
						$("#mensajeError").fadeOut("fast");
				}

				return valido;
		}

		function calcularDiffMeses(f1, f2){

				aF1 = f1.split( "/" );
				aF1 = aF1[ 2 ]+"/"+aF1[ 1 ]+"/"+aF1[ 0 ];
				aF1 = aF1.split( "/" );

				aF2 = f2.split( "/" );
				aF2 = aF2[ 2 ]+"/"+aF2[ 1 ]+"/"+aF2[ 0 ];
				aF2 = aF2.split( "/" );

				numMeses = parseInt(aF2[0])*12 + parseInt(aF2[1]) -
				 						(parseInt(aF1[0])*12 + parseInt(aF1[1]));
				if ( parseInt(aF2[2])<parseInt(aF1[2]) ){
					numMeses = numMeses - 1;
				}
				if ( numMeses > 6 ) {
						$("#mensajeError").html("El rango de fecha no debe ser mayor a 6 meses");
						$("#mensajeError").fadeIn("fast");
						return false;
				} else {
						return true;
				}

		}

		function CalculateDateDiff(dateFrom, dateTo) {

				var dateT = new Date(parseInt(dateTo.split("/")[2]),
						parseInt(dateTo.split("/")[1]), parseInt(dateTo.split("/")[0]));
				var dateF = new Date(parseInt(dateFrom.split("/")[2]),
						parseInt(dateFrom.split("/")[1]), parseInt(dateFrom.split("/")[0]));
				var difference = (dateT - dateF);

				var years = Math.floor(difference / (1000 * 60 * 60 * 24 * 365));
				difference -= years * (1000 * 60 * 60 * 24 * 365);
				var months = Math.floor(difference / (1000 * 60 * 60 * 24 * 30.4375));

				var dif = '';
				if (years > 0)
						dif = years + ' años ';

				if (months > 0) {
						if (years > 0)
								dif += ' y ';
						dif += months + ' meses';
				}

				if (months > 6) {
						$("#mensajeError").html("El rango de fecha no debe ser mayor a 6 meses");
						$("#mensajeError").fadeIn("fast");
						return false;
				} else {
						return true;
				}

				return true;
		}

		function downloadme(x) {
				myTempWindow = window.open(x, '', 'left=1000,screenX=1000');
				myTempWindow.document.execCommand('SaveAs', 'null', 'download.pdf');
		}

		function paginacion(total, inicial) {

				var texHtml = "";

				$("#list_pagination").html("");

				for (var i = 1; i <= total; ++i) {
						texHtml += '<span class="cajonNum"><a href="javascript:" id="page_' + i +
													'" class="num-pagina">' + i + '</a></span>';
				}

				$("#list_pagination").html(texHtml);
				$("#list_pagination").scrollLeft(0);

				ancho = $("#page_" + inicial).position().left - 4;

				$("#list_pagination").animate({scrollLeft: ancho}, 200);

				$(".num-pagina").css('text-decoration', 'none');
				$("#page_" + inicial).css('text-decoration', 'underline');

				$(".num-pagina").unbind("click");
				$(".num-pagina").click(function () {
						var id = this.id;
						id = id.split("_");
						buscarGuarderia(id[1]);
				});

				$("#anterior-1").unbind("mouseover");
				$("#anterior-1").unbind("mouseout");
				$("#anterior-1").mouseover(function () {
						scroll_interval = setInterval(
							function () {
									if ($("#list_pagination").scrollLeft() > 0) {
											ancho = $("#list_pagination").scrollLeft() - 1
											$("#list_pagination").scrollLeft(ancho);
									}
							}, 20);
				}).mouseout(function () {
						clearInterval(scroll_interval);
				});
				$("#anterior-2").unbind("mouseover");
				$("#anterior-2").unbind("mouseout");
				$("#anterior-2").mouseover(function () {
						scroll_interval = setInterval(
										function () {
												if ($("#list_pagination").scrollLeft() > 0) {
														ancho = $("#list_pagination").scrollLeft() - 1
														$("#list_pagination").scrollLeft(ancho);
												}
										}, 1);
				}).mouseout(function () {
						clearInterval(scroll_interval);
				});
				$("#siguiente-1").unbind("mouseover");
				$("#siguiente-1").unbind("mouseout");
				$("#siguiente-1").mouseover(function () {
						scroll_interval = setInterval(
										function () {
												ancho = $("#list_pagination").scrollLeft() + 1
												$("#list_pagination").scrollLeft(ancho);
										},
										20
										);
				}).mouseout(function () {
						clearInterval(scroll_interval);
				});
				$("#siguiente-2").unbind("mouseover");
				$("#siguiente-2").unbind("mouseout");
				$("#siguiente-2").mouseover(function () {
						scroll_interval = setInterval(
							function () {
									ancho = $("#list_pagination").scrollLeft() + 1
									$("#list_pagination").scrollLeft(ancho);
							},	1);
				}).mouseout(function () {
						clearInterval(scroll_interval);
				});

				$("#anterior-22").unbind("click");
				$("#anterior-22").click(function () {
						buscarGuarderia(1);
				});

				$("#siguiente-22").unbind("click");
				$("#siguiente-22").click(function () {
						buscarGuarderia(total);
				});
		}
		/***********************Paginacion fin***********************/

		calendario("Guarderia-fecha-in");
		calendario("Guarderia-fecha-fin");

		function calendario(input) {
				$("#" + input).datepicker({
						defaultDate: "+1w",
						changeMonth: true,
						changeYear: true,
						numberOfMonths: 1,
						dateFormat: "dd/mm/yy",
						maxDate: "+0D",
						onClose: function (selectedate) {
								if (input == 'Guarderia-fecha-in' && selectedate) {
										$("#Guarderia-fecha-fin").datepicker('option', 'minDate', selectedate);
								} else if (input == 'Guarderia-fecha-in') {
										$("#Guarderia-fecha-fin").datepicker('option', 'minDate', "");
								}
								if (input == 'Guarderia-fecha-fin' && selectedate) {
										$("#Guarderia-fecha-in").datepicker('option', 'maxDate', selectedate);
								} else if (input == 'Guarderia-fecha-fin') {
										$("#Guarderia-fecha-in").datepicker('option', 'maxDate', "+0D");
								}
						}
				});
		}

		function descargarArchivo(filtro_busq, url, titulo) {

				$aux = $("#cargando").dialog({title: titulo, modal: true, close: function () {
								$(this).dialog('close')
						}, resizable: false});

				$('form#formulario').empty();
				$('form#formulario').append('<input type="hidden" name="nombreEmpresa" value="' + filtro_busq.nombreEmpresa + '" />');
				$('form#formulario').append('<input type="hidden" name="fechaini" value="' + filtro_busq.Fechaini + '" />');
				$('form#formulario').append('<input type="hidden" name="fechafin" value="' + filtro_busq.Fechafin + '" />');
				$('form#formulario').append('<input type="hidden" name="acrif" value="' + filtro_busq.acrif + '" />');
				$('form#formulario').attr('action', url);
				$('form#formulario').submit();
				setTimeout(function () {
						$aux.dialog('destroy')
				}, 8000);
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
});
