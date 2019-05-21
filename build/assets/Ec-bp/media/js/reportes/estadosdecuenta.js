//---------------------------------------------------------
//SE ARMA LA URL PARA TRABAJARLA DENTRO DE TODO EL .JS
//---------------------------------------------------------
var scroll_interval;
var ancho=0;

//-------------------------------------------------------------------------
//VALIDACION PARA QUE SOLO PUEDAN BORRAR DATOS DE LOS CAMPOR DE FECHA
//-------------------------------------------------------------------------
$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});




// INICIO DEL DOCUMENTO
$(document).ready(function() {

	$("#repEstadosDeCuenta_dni").attr('maxlength','12');

//--------------------------
//LLENA EL COMBO DE EMPRESA
//--------------------------
		$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function( data ) {
			$("#cargando_empresa").fadeOut("slow");
			if(!(data.ERROR)){

				$.each(data.lista, function(k,v){

					$("#repEstadosDeCuenta_empresa").append('<option accodcia="'+v.accodcia+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" value="'+v.acrif+'">'+v.acnomcia+'</option>');
				});
			}else{
				if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
		         	$("#repEstadosDeCuenta_empresa").append('<option value="">'+data.ERROR+'</option>');
		         }
			}

			});
//---------------------------------------------------------
//LLENA EL COMBO DE PRODUCTO SEGUN LA SELECCION DE EMPRESA
//---------------------------------------------------------
		$("#repEstadosDeCuenta_empresa").on("change",function(){
			acrif = $('option:selected', this).attr("value");
			if(acrif){
			$("#repEstadosDeCuenta_producto").children( 'option:not(:first)' ).remove();
			$("#cargando_producto").fadeIn("slow");
			$(this).attr('disabled',true);
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$.post(baseURL + api + isoPais + "/producto/lista", { 'acrif': acrif, ceo_name: ceo_cook }, function(data){
				$("#cargando_producto").fadeOut("slow");
				$("#repEstadosDeCuenta_empresa").removeAttr('disabled');
				if(!data.ERROR){
					$.each(data, function(k,v){
						if(v.descripcion.toLowerCase().indexOf("bonus")==-1 && v.descripcion.toLowerCase().indexOf("provis")==-1 && v.descripcion.toLowerCase().indexOf("alimentacion")==-1 && v.descripcion.toLowerCase().indexOf("alimentación")==-1){
							$("#repEstadosDeCuenta_producto").append('<option value="'+v.idProducto+'" des="'+v.descripcion+"/" +v.marca.toUpperCase()+'" >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
						}

					});
				}else{
					if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
					$("#repEstadosDeCuenta_producto").append('<option value="">'+data.ERROR+'</option>');
				}
				}
			});
		}
		});




//----------------------------------------------------------------------------------
//MANEJOR DE DATE_PICKER DENTRO DEL FORMULARIO PARA CAMPOS DE FECHA_INI Y FECHA_FIN
//----------------------------------------------------------------------------------
		$( "#fecha_ini" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			dateFormat:"mm/yy",
			numberOfMonths: 1,
			maxDate: "+0D",
			onSelect: function( selectedDate,ins ) {
				fin = new Date(ins.selectedYear, ins.selectedMonth+1, 0);
				$( "#repEstadosDeCuenta_fecha_fin" ).val(fin.getDate()+'/'+(fin.getMonth()+1)+'/'+fin.getFullYear());
				$( "#repEstadosDeCuenta_fecha_ini" ).val('01/'+(fin.getMonth()+1)+'/'+fin.getFullYear());
			}
		});



//---------------------------------------------------------------
//VERIFICA SI LA BUSQUEDA ES POR CEDULA O POR CUALQUIER REGISTRO
//---------------------------------------------------------------
		$.each($(".radio"),function(pos,item){
			$(item).click(function(){
				if($(this).is(":checked")){
					if($(this).val()!="1"){
						$( "#repEstadosDeCuenta_dni" ).attr("disabled","true");
						$( "#repEstadosDeCuenta_dni" ).val("");
					}else{
						$( "#repEstadosDeCuenta_dni" ).removeAttr("disabled");
					}
				}
			});
		});

//------------------------------------------
//EJECUION DE LA BUSQUEDA DE LA INFORMACION
//------------------------------------------
		$("#repEstadosDeCuenta_btnBuscar").click(function(){ /*$('#paginacion').hide();*/ $('#contend-pagination').hide(); buscarReporte=true; BuscarEstadosdeCuenta(0)});


//---------------------------------------------------------
//FUNCION PARA VALIDAR CADA UNO DE LOS TIPOS DE INPUT
//---------------------------------------------------------
	function validar_filtro_busqueda(div){
		var valido=true;
		//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		 $.each($("#"+div+" input[type='text'].required"),function(posItem,item){
		       var $elem=$(item);
		       if(!$elem.hasClass("bloqued")){
			       	if($elem.val()==""){
			                valido=false;
			                $elem.attr("style","border-color:red");
			        }else{
			                $elem.attr("style","");
			        }
		       }

		  });

		//VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		$.each($("#"+div+" select.required"),function(posItem,item){
			var $elem=$(item);
			if($elem.val()==""){
				valido=false;
				$elem.attr("style","border-color:red");
			}else{
				$elem.attr("style","");
			}
		});


		//VALIDA INPUT:CHECKBOX  y INPUT:RADIO QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		var check = $("#"+div+" input[type='checkbox'].required:checked").length;
		var radio = $("#"+div+" input[type='radio'].required:checked ").length;
		if((check == "")&&($("#"+div+" input[type='checkbox'].required").length!="")){
			valido=false;
			$("#"+div+" input[type='checkbox'].required").next().attr("style","color:red");
		}else{
			$("#"+div+" input[type='checkbox'].required").next().attr("style","");
		}

		if((radio == "")&&($("#"+div+" input[type='radio'].required").length!="")){
			valido=false;
			$("#"+div+" input[type='radio'].required").next().attr("style","color:red");
		}else{
			$("#"+div+" input[type='radio'].required").next().attr("style","");
		}


		if(!valido){
			$(".div_tabla_detalle").fadeOut("fast");
			showErrMsg();
		}else{
			$("#mensajeError").fadeOut("fast");
		}


		return valido;
	}



//---------------------------------------------------------
//FUNCION QUE INICIA LA BUSQUEDA DE Estado de cuenta
//---------------------------------------------------------
var filtro_busq={};
function BuscarEstadosdeCuenta(paginaActual){

	var $consulta;
	var valid=false;

//SETEA EL VALOR DE PAGINA ACTUAL EN CASO DE QUE SEA EL PRIMER INTENTO DE CONSULTA
	if (paginaActual == 0 ){
		paginaActual = 1;
	}
//SE VALIDAN TODOS LOS CAMPOS DENTRO DEL DIV LOTES-2

	if (buscarReporte) {
		valid=validar_filtro_busqueda("lotes-2");
		filtro_busq.empresa=$("#repEstadosDeCuenta_empresa").val();
		filtro_busq.fechaInicial=$("#repEstadosDeCuenta_fecha_ini").val();
		filtro_busq.fechaFin=$("#repEstadosDeCuenta_fecha_fin").val();
		filtro_busq.cedula=$("#repEstadosDeCuenta_dni").val().replace(/ /g,'');
		filtro_busq.producto=$("#repEstadosDeCuenta_producto").val();
		filtro_busq.tipoConsulta=$("input[name='radio']:checked").val();

		filtro_busq.acrif = $('option:selected', "#repEstadosDeCuenta_empresa").attr("value");
		filtro_busq.acnomcia = $('option:selected', "#repEstadosDeCuenta_empresa").attr("acnomcia");
		filtro_busq.productoDesc = $('option:selected', "#repEstadosDeCuenta_producto").attr("des");
	}else{
		valid=true;
	}

//SE MUESTRA EL GIF DE CARGANDO DEBAJO DEL FORMULARIO EN CASO DE QUE EL FORMULARIO SEA VALIDO

	if(valid){
		var form = $('#form-criterio-busqueda');
		validateForms(form);
		if(form.valid()) {

//SE MUESTRA EL GIF DE CARGANDO DEBAJO DEL FORMULARIO EN CASO DE QUE EL FORMULARIO SEA VALIDO
		$('#cargando').fadeIn("slow");
		$("#repEstadosDeCuenta_btnBuscar").hide();
			$('#div_tablaDetalle').fadeOut("fast");

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		filtro_busq.paginaActual=paginaActual;
		filtro_busq.ceo_name = ceo_cook;
		$consulta = $.post(baseURL + api + isoPais + "/reportes/estadosdecuenta",filtro_busq );
//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"

 		$consulta.done(function(data){

// SE OCULTA EL GIF DE CARGANDO Y SE MUESTRA EL CONTENEDOR DE LA TABLA
		$("#mensaje").remove();
		$("#view-results").attr("style","");
		$("#div_tablaDetalle").fadeIn("slow");
		$('#cargando').fadeOut("slow");
		$("#repEstadosDeCuenta_btnBuscar").show();

// SE INICIALIZAN LAS VARIABLES NECESARIAS PARA ARMAR LA TABLA
		var tbody=$("#tbody-datos-general");
		var contenedor = $("#div_tablaDetalle");

		if(buscarReporte){
		contenedor.empty();


		}

		var tr;
		var td;
		var tabla;
		var a;
		var div;
		var span;




//SI EL CODIGO DE LA RESPUESTA ES 0 SE REALIZO LA PETICION CON EXITO Y TRAJO INFORMACION
		 if(data.rc == "0"){
		 	//$('#paginacion').show();
		 	$('#contend-pagination').show();
		 if(buscarReporte){
		contenedor.empty();

		}

//CARGA DE VARABLE CON LOS CODIGOS DE CARGOS Y ABONOS DEVUELTOS POR EL SERVICIOS, NECESARIOS
//PARA VERIFICAR EN QUE COLUMNA IRAN LOS MONTOS
		 var abonos = data.codAbonos.split("|");
		 var cargos = data.codCargos.split("|");

if(buscarReporte){
//CREAN LOS ELEMENTOS DEL DOM PRINCIPALES PARA LA INFORMACION DIV Y TABLE
		div=$(document.createElement("div")).appendTo(contenedor);
		div.attr("id","view-results");
		div.attr("style","padding-right:20px;");


//CREA LA CABECERA DE LA TABLA JUNTO CON LOS ICONOS
		contenedor.html('<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe05c;"></span> Resultados Estado de cuenta </div>');
		div=$(document.createElement("div")).appendTo(contenedor);
		div.attr("id","view-results");
		div.attr("style","padding-right:20px;");

		a=$(document.createElement("a")).appendTo(div);
		a.attr("id","export_excel_a");
		span=$(a).append("<span id='export_excel' title = 'Exportar Excel' data-icon ='&#xe05a;' aria-hidden = 'true' class = 'icon'></span>");

		span.attr("aria-hidden","true");
		span.attr("class","icon");
		span.attr("data-icon",'&#xe05a;');
		span.attr("title","Exportar a EXCEL");
		span.click(function(){


			/*datos={
				empresa:filtro_busq.empresa,
				fechaInicial: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				cedula: filtro_busq.cedula.replace(/ /g,''),
				paginaActual: 1,
				producto: filtro_busq.producto,
				tipoConsulta: filtro_busq.tipoConsulta,
				nomEmpresa: filtro_busq.acnomcia.replace(","," "),
				descProducto: filtro_busq.productoDesc
			}
			descargarArchivo(datos, baseURL+api+isoPais+"/reportes/EstadosdeCuentaXLS", "Exportar a EXCEL" );*/
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
						$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    				$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
    				$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
    				$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
    				$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
    				$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
    				$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia.replace(","," ")+'" />');
    				$('form#formulario').append('<input type="hidden" name="descProducto" value="'+filtro_busq.productoDesc+'" />');
    				$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/EstadosdeCuentaXLS");
    				$('form#formulario').submit()



		});

		a=$(document.createElement("a")).appendTo(div);
		a.attr("id","export_pdf_a");
		span=$(a).append("<span id = 'export_pdf' title='Exportar a PDF' data-icon ='&#xe02e;' aria-hidden = 'true' class = 'icon'></span>");

		span.attr("aria-hidden","true");
		span.attr("class","icon");
		span.attr("data-icon",'&#xe02e;');
		span.attr("title","Exportar a PDF");
		span.click(function(){


			/*datos={
				empresa:filtro_busq.empresa,
				fechaInicial: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				cedula: filtro_busq.cedula.replace(/ /g,''),
				paginaActual: 1,
				producto: filtro_busq.producto,
				tipoConsulta: filtro_busq.tipoConsulta,
				nomEmpresa: filtro_busq.acnomcia.replace(","," "),
				descProducto: filtro_busq.productoDesc
			}
			descargarArchivo(datos, baseURL+api+isoPais+"/reportes/EstadosdeCuentaPDF", "Exportar a PDF" );*/
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
						$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    				$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
    				$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
    				$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
    				$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
    				$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
    				$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia.replace(","," ")+'" />');
    				$('form#formulario').append('<input type="hidden" name="descProducto" value="'+filtro_busq.productoDesc+'" />');
    				$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/EstadosdeCuentaPDF");
    				$('form#formulario').submit()

		});

		var abono, cargo, titulografico, produc, moneda;
		abono = $("#abono").attr("data");
		cargo = $("#cargo").attr("data");
		titulografico = $("#titulografico").attr("data");
		produc = $("option:selected","#repEstadosDeCuenta_producto").attr("des");
		moneda = $("#moneda").attr("data");

		a=$(document.createElement("a")).appendTo(div);
		span=$(a).append("<span data-icon ='&#xe050' aria-hidden = 'true' class = 'icon'></span>");
		span.attr("aria-hidden","true");
		span.attr("class","icon");
		span.attr("data-icon",'&#xe050;');
		span.attr("title","Generar gráfica");
		span.click(function(){

//SE EJECUTA LA CONSULTA PARA EL GRAFICO
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				filtro_busq.ceo_name = ceo_cook;
				$consulta = $.post(baseURL + api + isoPais + "/reportes/EstadosdeCuentaGrafico",filtro_busq );
// APARECE LA VENTANA DE CARGANDO MIENTRAS SE REALIZA LA CONSULTA
				$( "#cargando" ).dialog({title:"Ver Gráfica Estado de cuenta",modal:true, width: 200, height: 170});
//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"
		 		$consulta.done(function(data){

			 		$( "#cargando" ).dialog("destroy");

			 		if(data.rc=="0"){

		 			var aux={};
					var _axis="Bolivares";

					var jsonChart={
						title:{
							text:titulografico + " " + produc
						},
						seriesDefaults: {
					         labels: {
					         template: "#= category # - #= kendo.format('{0:P}', percentage)#",
					         position: "outsideEnd",
					         visible: true,
					         background: "transparent"
					        }
					    },
						legend:{
							position:"bottom"
						},
						seriesDefaults: {
					        labels: {
					        visible: true,
					        format: moneda+".{0}"
					        }
					    },
						series:[{
						type: "pie",
						data:[]
						}],
						categoryAxis:{
							categories:[],
							labels: {
								rotation: -45
							}
						},
						tooltip: {
				          visible: true,
				          template: "#= category # - #= kendo.format('{0:P}', percentage) #"
				         }

					}

//SE LLENA EL JSON CON LAS CATEGORIAS
					$.each(data.listaGrafico[0].categorias,function(posLista,itemLista){
						jsonChart.categoryAxis.categories.push(itemLista.nombreCategoria);
					});


// SE LLENAN LA SERIES NECESARIAS PARA LAS GRAFICAS
					var datos = data;
					var data={};

						data.color = "#54C2D0";
						data.category=abono;
						data.value=datos.listaGrafico[0].series[0].valores[0];
						jsonChart.series[0].data.push(data);

						data={};
						data.color = "#50C592";
						data.category=cargo;
						data.value=datos.listaGrafico[0].series[0].valores[1];
						jsonChart.series[0].data.push(data);

// SE MUESTRA EL JSON TERMINADO PARA VER QUE TODO ESTA BIEN Y SE MUESTRA LA GRAFICA DENTRO DE UNA VENTANA MODAL

							        $("#chart").kendoChart(jsonChart);
									$("#chart").dialog({modal:true, width: 600, height: 400});
									$("#chart svg").width(Number($(window).width()));
							        $("#chart svg").height(Number($(window).height()));
							        $("#chart").data("kendoChart").refresh();
					}else{
						if(data.rc=='-29'||data.rc=='-61'){
							alert("Usuario actualmente desconectado"); location.reload();
						}else{
							notificacion("Ver Gráfica Estado de cuenta",data.mensaje);
						}
					}

					});

		 		});

//

		a=$(document.createElement("a")).appendTo(div);
		a.attr("id","export_mosivo_a");
		span=$(a).append("<span id = 'export_mosivo' title='Exportar masivo' data-icon ='&#xe009;' aria-hidden = 'true' class = 'icon'></span>");

		span.attr("aria-hidden","true");
		span.attr("class","icon");
		span.attr("data-icon",'&#xe009;;');
		span.attr("title","Generar Comprobante Masivo");

		span.click(function(){

			/*datos={
				empresa:filtro_busq.empresa,
				fechaInicial: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				cedula: filtro_busq.cedula.replace(/ /g,''),
				paginaActual: 1,
				producto: filtro_busq.producto,
				tipoConsulta: filtro_busq.tipoConsulta,
				nomEmpresa: filtro_busq.acnomcia.replace(","," "),
				descProducto: filtro_busq.productoDesc
			}
			descargarArchivo(datos, baseURL+api+isoPais+"/reportes/EstadosdeCuentaMasivo", "Generar Comprobante Masivo" );*/
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
						$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    				$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
    				$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
    				$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
    				$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
    				$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
    				$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia.replace(","," ")+'" />');
    				$('form#formulario').append('<input type="hidden" name="descProducto" value="'+filtro_busq.productoDesc+'" />');
    				$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/EstadosdeCuentaMasivo");
    				$('form#formulario').submit()


		});

}
		if( !$('.EC-container').hasClass('rpg'+paginaActual) ){

		 $.each(data.listadoEstadosCuentas,function(posLista,dataitem){
		 		div=$(document.createElement("div")).appendTo(contenedor);
		 		div.attr("class","EC-container rpg"+paginaActual);
		 		div.attr("style","width:650px;margin-top:100px");
 				tabla=$(document.createElement("table")).appendTo(div);
 				tabla.attr("id","tabla-datos-general");
 				tabla.attr("class","tabla-reportes trpg"+paginaActual);
 				thead=$(document.createElement("thead")).appendTo(tabla);
 				thead.attr("class","thead-edc");

 				tr=$(document.createElement("tr")).appendTo(thead);
				tr.attr("id","Datos1");
				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#cuenta").attr("data")+dataitem.cuenta);
 				th.attr("id", "Datos1-long");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#cedula").attr("data")+dataitem.idExtPer);
 				th.attr("id", "Datos1-long");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#cliente").attr("data")+dataitem.cliente);
 				th.attr("id", "Datos1-long");

 				tr=$(document.createElement("tr")).appendTo(thead);
				tr.attr("id","Datos2");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#tarjeta").attr("data"));
 				th.attr("id", "Datos2-long");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#fecha").attr("data"));
 				th.attr("id", "Datos2-med");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#referencia").attr("data"));
 				th.attr("id", "Datos2-med");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#descripcion").attr("data"));
 				th.attr("id", "Datos2-long");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#abono").attr("data"));
 				th.attr("id", "Datos2-short");
 				th=$(document.createElement("th")).appendTo(tr);
 				th.html($("#cargo").attr("data"));
 				th.attr("id", "Datos2-short");

	 			tbody=$(document.createElement("tbody")).appendTo(tabla);
	 			$.each(dataitem.listaMovimientos,function(posLista,item){

					tr=$(document.createElement("tr")).appendTo(tbody);
					tr.attr("id","Datos2");
	 				td=$(document.createElement("td")).appendTo(tr);
	 				td.html("********"+item.tarjeta);
	 				td.attr("id", "Datos2-long");
	 				td=$(document.createElement("td")).appendTo(tr);
	 				td.html(item.fecha);
	 				td.attr("id", "Datos2-med");
	 				td=$(document.createElement("td")).appendTo(tr);
	 				td.html(item.referencia);
	 				td.attr("id", "Datos2-med");
	 				td=$(document.createElement("td")).appendTo(tr);
	 				td.html(item.descripcion);
	 				td.attr("id", "Datos2-long");

	 				$.each(abonos,function(pos,valor){
		 				if(item.codigo == valor){
			 				td=$(document.createElement("td")).appendTo(tr);
			 				a= $(document.createElement("a")).appendTo(td);
								a.attr("paginaActual",paginaActual);
								a.attr("tarjeta",item.tarjeta.replace(",",""));
           						a.attr("fecha",item.fecha);
           						a.attr("referencia",item.referencia);
           						a.attr("descripcion",item.descripcion);
            					a.attr("monto",item.monto);
           						a.attr("cliente",item.cliente);

			 				a.attr("title","Generar comprobante de abono");
			 				a.html(item.monto);
			 				td.attr("id", "Datos2-short");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html("0");
			 				td.attr("id", "Datos2-short");
		 				}
	 				});

	 				$("table a").on('click',function(){

	 					/*datos = {
	 						empresa:filtro_busq.empresa,
	 						fechaInicial:filtro_busq.fechaInicial,
	 						fechaFin:filtro_busq.fechaFin,
	 						cedula:filtro_busq.cedula.replace(/ /g,''),
	 						paginaActual:$(this).attr("paginaActual"),
	 						producto:filtro_busq.producto,
	 						tipoConsulta:filtro_busq.tipoConsulta,
	 						tarjeta:$(this).attr("tarjeta"),
	 						fecha:$(this).attr("fecha"),
	 						referencia:$(this).attr("referencia"),
	 						descripcion:$(this).attr("descripcion"),
	 						monto:$(this).attr("monto"),
	 						nomEmpresa:filtro_busq.acnomcia.replace(","," "),
	 						cliente:$(this).attr("cliente")
	 					}

	 					descargarArchivo(datos, baseURL+api+isoPais+"/reportes/EstadosdeCuentaComp", "Generar Comprobante de pago" );*/
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						$('form#formulario').empty();
						$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    				$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
    				$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
    				$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
    				$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
    				$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
    				$('form#formulario').append('<input type="hidden" name="tarjeta" value="'+$(this).attr("tarjeta")+'" />');
    				$('form#formulario').append('<input type="hidden" name="fecha" value="'+$(this).attr("fecha")+'" />');
    				$('form#formulario').append('<input type="hidden" name="referencia" value="'+$(this).attr("referencia")+'" />');
    				$('form#formulario').append('<input type="hidden" name="descripcion" value="'+$(this).attr("descripcion")+'" />');
    				$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
    				$('form#formulario').append('<input type="hidden" name="monto" value="'+$(this).attr("monto")+'" />');
    				$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia.replace(","," ")+'" />');
    				$('form#formulario').append('<input type="hidden" name="cliente" value="'+$(this).attr("cliente")+'" />');
    				$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/EstadosdeCuentaMasivo");
    				$('form#formulario').submit()


	 				})



	 				$.each(cargos,function(pos,valor){
		 				if(item.codigo == valor){
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html("0");
			 				td.attr("id", "Datos2-short");
		 					td=$(document.createElement("td")).appendTo(tr);
			 				td.html(item.monto);
			 				td.attr("id", "Datos2-short");
		 				}
	 				});


	 	});

				var tfoot = $(document.createElement("tfoot")).appendTo(tabla);
				tr = $(document.createElement("tr")).appendTo(tfoot);
				tr.attr("id","Datos3");
 				td=$(document.createElement("td")).appendTo(tr);
 				td.html("Totales");
 				td.attr("id", "Datos3-long");
 				td=$(document.createElement("td")).appendTo(tr);
 				td.html(dataitem.totalAbonos);
 				td.attr("id", "Datos3-short");
 				td=$(document.createElement("td")).appendTo(tr);
 				td.html(dataitem.totalCargos);
 				td.attr("id", "Datos3-short");

			 	});


			$('#tabla-datos-general tbody tr:even').addClass('even ');

			/*
			if(buscarReporte){
			   paginar(data.totalPaginas, data.pagActual);
			   buscarReporte=false;
			}
			*/

			paginacion(data.totalPaginas, data.pagActual);

			$('#div_tablaDetalle .trpg'+paginaActual).dataTable( {
		         "iDisplayLength": 5,
		         'bDestroy':true,
		         "bFilter": false,
				"bLengthChange": false,
		         "sPaginationType": "full_numbers",
		         "oLanguage": {
		           "sProcessing":     "Procesando...",
		           "sLengthMenu":     "Mostrar _MENU_ registros",
		           "sZeroRecords":    "No se encontraron resultados",
		           "sEmptyTable":     "Ningún dato disponible en esta tabla",
		           "sInfo":           "Mostrando registros del _START_ al _END_, de un total de _TOTAL_ registros",
		           "sInfoEmpty":      "Mostrando registros del 0 al 0, de un total de 0 registros",
		           "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		           "sInfoPostFix":    "",
		           "sSearch":         "Buscar:",
		           "sUrl":            "",
		           "sInfoThousands":  ",",
		           "sLoadingRecords": "Cargando...",
		           "oPaginate": {
		               "sFirst":    "<<",
		               "sLast":     ">>",
		               "sNext":     ">",
		               "sPrevious": "<"
		           }
		         }
		        });

				}

			}else{
				if(data.rc =="-29"){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{

		 			$("#mensaje").remove();
		 			contenedor.html('<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe05c;"></span>Estado de cuenta </div>');
		 			$("#tabla-datos-general").fadeOut("fast");
		 			$("#view-results").attr("style","display:none");
		 			var div =$(document.createElement("div")).appendTo(contenedor);
		 			div.attr("id","mensaje");
		 			div.attr("style","background-color:rgb(252,199,199); margin-top:62px;");
		 			var p = $(document.createElement("p")).appendTo(div);
		 			p.html(data.mensaje);
		 			p.attr("style","text-align:center;padding:10px;font-size:14px");
		 		}
			}


		});
		} else {
			showErrMsg('Verifique los datos ingresados e intente nuevamente');
		}
}

}

	/*
	function paginar(totalPaginas, paginaActual) {
		$("#paginacion").paginate({
			count 		: totalPaginas,
			start 		: paginaActual,
			display     : 10,
			border					: false,
			text_color  			: '#79B5E3',
			background_color    	: 'none',
			text_hover_color  		: '#2573AF',
			background_hover_color	: 'none',
			images		: false,
			mouse		: 'press',
			onChange     			: function(page){

										if( !$('.EC-container').hasClass('rpg'+page) ){
											$('#paginacion').hide();
											BuscarEstadosdeCuenta(page);
										}
										$('#div_tablaDetalle .EC-container').hide();
											$('#div_tablaDetalle .rpg'+page).show();

									  }
		});
	}
	*/
/***********************Paginacion inicio***********************/
	function paginacion(total, inicial){
		var texHtml="";
		$("#list_pagination").html("");
			for(var i=1;i<=total;++i) {
					texHtml+='<span class="cajonNum"><a href="javascript:" id="page_'+ i +'" class="num-pagina">'+ i +'</a></span>';
			}
		$("#list_pagination").html(texHtml);

		$("#list_pagination").scrollLeft(0);

		ancho = $("#page_"+ inicial).position().left - 4;

		$("#list_pagination").animate({
	        scrollLeft: ancho
	    }, 200);

		$(".num-pagina").css('text-decoration','none');
		$("#page_"+ inicial).css('text-decoration','underline');

		$(".num-pagina").unbind("click");
		$(".num-pagina").click(function(){
			var id = this.id;
				id = id.split("_");
			BuscarEstadosdeCuenta(id[1]);
		});

		$("#anterior-1").unbind("mouseover");
		$("#anterior-1").unbind("mouseout");
		$("#anterior-1").mouseover(function(){
			scroll_interval = setInterval(
			function() {
				if($("#list_pagination").scrollLeft()>0){
				  ancho = $("#list_pagination").scrollLeft() - 1
				  $("#list_pagination").scrollLeft(ancho);
				}
			}, 20);
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#anterior-2").unbind("mouseover");
		$("#anterior-2").unbind("mouseout");
		$("#anterior-2").mouseover(function(){
			scroll_interval = setInterval(
			function() {
				if($("#list_pagination").scrollLeft()>0){
				  ancho = $("#list_pagination").scrollLeft() - 1
				  $("#list_pagination").scrollLeft(ancho);
				}
			}, 1);
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#siguiente-1").unbind("mouseover");
		$("#siguiente-1").unbind("mouseout");
		$("#siguiente-1").mouseover(function(){
			scroll_interval = setInterval(
					function() {
					  ancho = $("#list_pagination").scrollLeft() + 1
					  $("#list_pagination").scrollLeft(ancho);
					},
					20
				  );
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#siguiente-2").unbind("mouseover");
		$("#siguiente-2").unbind("mouseout");
		$("#siguiente-2").mouseover(function(){
			scroll_interval = setInterval(
					function() {
					  ancho = $("#list_pagination").scrollLeft() + 1
					  $("#list_pagination").scrollLeft(ancho);
					},
					1
				  );
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});

		$("#anterior-22").unbind("click");
		$("#anterior-22").click(function(){
			BuscarEstadosdeCuenta(1);
		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22").click(function(){
			BuscarEstadosdeCuenta(total);
		});

	}
/***********************Paginacion fin***********************/

function descargarArchivo(datos, url, titulo){

	$aux = $("#cargando").dialog({title:titulo,modal:true, close:function(){$(this).dialog('close')}, resizable:false });
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	datos.ceo_name = ceo_cook;
			$.post(url,datos).done(function(data){
    			$aux.dialog('destroy')
    			if(!data.ERROR){
						$('form#formulario').empty();
						$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    				$('form#formulario').append('<input type="hidden" name="bytes" value="'+JSON.stringify(data.bytes)+'" />');
    				$('form#formulario').append('<input type="hidden" name="ext" value="'+data.ext+'" />');
    				$('form#formulario').append('<input type="hidden" name="nombreArchivo" value="'+data.nombreArchivo+'" />');
    				$('form#formulario').attr('action',baseURL+isoPais+"/file");
    				$('form#formulario').submit()
    			}else{
    				if(data.ERROR=="-29"){
    					alert('Usuario actualmente desconectado');
						location.reload();
    				}else{
    					notificacion(titulo,data.ERROR)
    				}

    			}
    		})

}

function notificacion(titulo, mensaje){

  var canvas = "<div>"+mensaje+"</div>";

  $(canvas).dialog({
    title: titulo,
    modal: true,
    maxWidth: 700,
    maxHeight: 300,
    resizable: false,
    close:function(){
      $(this).dialog("destroy");
    },
    buttons: {
      OK: function(){
            $(this).dialog("destroy");
          }
    }
  });
}



});
