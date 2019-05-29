//---------------------------------------------------------
//SE ARMA LA URL PARA TRABAJARLA DENTRO DE TODO EL .JS
//---------------------------------------------------------

$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});

//---------------------------------------------------------
//ARREGLO DE COLORES PARA USAR AL GENERAR LA GRAFICA
//---------------------------------------------------------
var colores = ["#298C9A","#522551","#50C592","#54C2D0","#2B569F","#2C855F","#F5921E","#994596","#1A325B","#B46607"];

//-------------------------------------------------------------------------
//VALIDACION PARA QUE SOLO PUEDAN BORRAR DATOS DE LOS CAMPOR DE FECHA
//-------------------------------------------------------------------------
$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});

//---------------------------------------------------------
// CODIGO PARA LLENAR EL COMBO DE AÑO DENTRO DEL FORMULARIO
//---------------------------------------------------------
var fecha = new Date();
fecha = fecha.getFullYear();
var i=0;
var anio;
do{
	anio= parseInt(fecha)-i;
    $("#repGastosPorCategoria_anio").append('<option value="'+anio.toString()+'">'+anio.toString()+'</option>');
	i=i+1;
}while(i!=20);


// INICIO DEL DOCUMENTO
$(document).ready(function() {

$("#repGastosPorCategoria_dni").attr("maxlength","12");
//--------------------------
//LLENA EL COMBO DE EMPRESA
//--------------------------
	$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function(response) {
			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
		$("#cargando_empresa").fadeOut("slow");
	if(!(data.ERROR)){

		$.each(data.lista, function(k,v){
			$("#repGastosPorCategoria_empresa").append('<option accodcia="'+v.accodcia+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" value="'+v.acrif+'">'+v.acnomcia+'</option>');
		});
	}else{
		if(data.ERROR.indexOf('-29') !=-1){
             alert("Usuario actualmente desconectado");
             $(location).attr('href',baseURL+isoPais+'/login');
         }else{
         	$("#repGastosPorCategoria_empresa").append('<option value="">'+data.ERROR+'</option>');
         }
	}
	});
//---------------------------------------------------------
//LLENA EL COMBO DE PRODUCTO SEGUN LA SELECCION DE EMPRESA
//---------------------------------------------------------
	$("#repGastosPorCategoria_empresa").on("change",function(){
		acrif = $('option:selected', this).attr("value");
		if(acrif){
		$("#repGastosPorCategoria_producto").children( 'option:not(:first)' ).remove();

		$("#cargando_producto").fadeIn("slow");
		$(this).attr('disabled',true);

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var dataRequest = JSON.stringify ({
			acrif: acrif
		})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
			$.post(baseURL + api + isoPais + "/producto/lista", {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)})
		  .done(function(response){
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
			$("#cargando_producto").fadeOut("slow");
			$("#repGastosPorCategoria_empresa").removeAttr('disabled');
			if(!data.ERROR){
				$.each(data, function(k,v){
					if(v.descripcion.toLowerCase().indexOf("bonus")==-1 && v.descripcion.toLowerCase().indexOf("provis")==-1 && v.descripcion.toLowerCase().indexOf("alimentacion")==-1 && v.descripcion.toLowerCase().indexOf("alimentación")==-1){
						$("#repGastosPorCategoria_producto").append('<option value="'+v.idProducto+'" des="'+v.descripcion+'">'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
					}
				});
			}else{
				if(data.ERROR.indexOf('-29') !=-1){
             alert("Usuario actualmente desconectado");
             $(location).attr('href',baseURL+isoPais+'/login');
         }else{
				$("#repGastosPorCategoria_producto").append('<option value="">'+data.ERROR+'</option>');
			}
			}
		});
	}
	});


//--------------------------------------------------------------------------------------
//VALIDACION DE CAMPOS DE FECHA DE3 INICIO Y FECHA FIN SEGUN EL RADIO DE ANUAL O MENSUAL
//--------------------------------------------------------------------------------------
	$.each($(".radio"),function(pos,item){
		$(item).click(function(){
			if($(this).is(":checked")){
				if($(this).val()=="0"){
					$( "#repGastosPorCategoria_fecha_ini" ).attr("disabled","true");
					$( "#repGastosPorCategoria_fecha_fin" ).attr("disabled","true");
					$( "#repGastosPorCategoria_anio" ).removeAttr("disabled");
					$("#repGastosPorCategoria_anio [selected='selected']").val("");
				}else{
					$( "#repGastosPorCategoria_anio" ).attr("disabled","true");
					$( "#repGastosPorCategoria_anio [selected='selected']").val("0");
					$( "#repGastosPorCategoria_fecha_ini" ).removeAttr("disabled");
					$( "#repGastosPorCategoria_fecha_fin" ).removeAttr("disabled");
				}
			}
		});
	});


//----------------------------------------------------------------------------------
//MANEJOR DE DATE_PICKER DENTRO DEL FORMULARIO PARA CAMPOS DE FECHA_INI Y FECHA_FIN

selIn=false; selFi=false;
$( "#repGastosPorCategoria_fecha_ini" ).datepicker({
      defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        dateFormat:"dd/mm/yy",
        numberOfMonths: 1,
      maxDate:"+0D",
      onClose: function( selectedDate, inst ) {

          if(selIn){

          sumaMes = new Date($('#repGastosPorCategoria_fecha_ini').datepicker('getDate').getTime()+90*24*60*60*1000);

          new Date < sumaMes ? sumaMes= new Date : sumaMes=sumaMes;
          selIn=false;
        }else{
          $('#repGastosPorCategoria_fecha_ini').val("");
          selectedDate=""; sumaMes="+0D";
        }

         $( "#repGastosPorCategoria_fecha_fin" ).datepicker( 'option', 'minDate', selectedDate );
         $( "#repGastosPorCategoria_fecha_fin" ).datepicker( 'option', 'maxDate', sumaMes);
      },
      onSelect: function(){
        selIn=true;
      }
    });

    $( "#repGastosPorCategoria_fecha_fin" ).datepicker({
      defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        dateFormat:"dd/mm/yy",
        numberOfMonths: 1,
      maxDate:"+0D",
      onClose: function( selectedDate ) {

        if(selFi){
        restaMes = new Date($('#repGastosPorCategoria_fecha_fin').datepicker('getDate').getTime()-90*24*60*60*1000);
        selFi=false;
      }else{
        $( "#repGastosPorCategoria_fecha_fin" ).val("");
        selectedDate="+0D"; restaMes="";
      }

        $( "#repGastosPorCategoria_fecha_ini" ).datepicker( 'option', 'minDate', restaMes );
        $( "#repGastosPorCategoria_fecha_ini" ).datepicker( 'option', 'maxDate', selectedDate );
      },
      onSelect: function(){
        selFi=true;
      }
    });

//--------------------------
//MENEJO DEL EXPORTAR A PDF
//--------------------------
		$(".exportPDF_a").click(function(){

			/*datos = {
				empresa: filtro_busq.empresa,
				fechaIni: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				producto: filtro_busq.producto,
				tarjeta: filtro_busq.tarjeta.replace(/ /g,''),
				cedula: filtro_busq.cedula.replace(/ /g,''),
				tipoConsulta: filtro_busq.tipoConsulta
			}

			descargarArchivo(datos, baseURL+api+isoPais+"/reportes/gastosporcategoriasExpPDF", "Exportar PDF" );
*/
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaIni" value="'+filtro_busq.fechaInicial+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
			$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
			$('form#formulario').append('<input type="hidden" name="tarjeta" value="'+filtro_busq.tarjeta.replace(/ /g,'')+'" />');
			$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
			$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
			$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/gastosporcategoriasExpPDF");
			$('form#formulario').submit();

		});

//---------------------------
//MANEJO DEL EXPORTAR A XLS
//---------------------------
		$(".exportXLS_a").click(function(){

		// 	datos = {
		// 		empresa: filtro_busq.empresa,
		// 		fechaIni: filtro_busq.fechaInicial,
		// 		fechaFin: filtro_busq.fechaFin,
		// 		producto: filtro_busq.producto,
		// 		tarjeta: filtro_busq.tarjeta.replace(/ /g,''),
		// 		cedula: filtro_busq.cedula.replace(/ /g,''),
		// 		tipoConsulta: filtro_busq.tipoConsulta
		// 	}

		// 	descargarArchivo(datos, baseURL+api+isoPais+"/reportes/gastosporcategoriasExpXLS", "Exportar Excel" );

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

		$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaIni" value="'+filtro_busq.fechaInicial+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
			$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
			$('form#formulario').append('<input type="hidden" name="tarjeta" value="'+filtro_busq.tarjeta.replace(/ /g,'')+'" />');
			$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula.replace(/ /g,'')+'" />');
			$('form#formulario').append('<input type="hidden" name="tipoConsulta" value="'+filtro_busq.tipoConsulta+'" />');
			$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/gastosporcategoriasExpXLS");
			$('form#formulario').submit();
	});


//------------------------------------------
//EJECUION DE LA BUSQUEDA DE LA INFORMACION
//------------------------------------------
var filtro_busq={};
	$("#repGastosPorCategoria_btnBuscar").click(function(){

		var $consulta;

//SE VALIDAN TODOS LOS CAMPOS DENTRO DEL DIV LOTES-2
		if(validar_filtro_busqueda("lotes-2")){

//SE MUESTRA EL GIF DE CARGANDO DEBAJO DEL FORMULARIO EN CASO DE QUE EL FORMULARIO SEA VALIDO
			$('#cargando').fadeIn("slow");
			$(this).hide();
		    $('#div-anio').fadeOut("fast");
		    $('#div-mes').fadeOut("fast");
		    $("#div_tablaDetalle").fadeOut("fast");

//SE LLENA EL JSON CON LA INFORMACION DE DE LOS CAMPOS DEL FORMULARIO
			filtro_busq.empresa=$("#repGastosPorCategoria_empresa").val();
			if($("#anual").is(":checked")){
				var anio = $("#repGastosPorCategoria_anio").val();
				filtro_busq.fechaInicial="01/01/"+anio;
				filtro_busq.fechaFin="31/12/"+anio;
			}else{
				filtro_busq.fechaInicial=$("#repGastosPorCategoria_fecha_ini").val();
				filtro_busq.fechaFin=$("#repGastosPorCategoria_fecha_fin").val();
			}
			filtro_busq.producto=$("#repGastosPorCategoria_producto").val();
			filtro_busq.tarjeta=$("#repGastosPorCategoria_tarjeta").val().replace(/ /g,'');
			filtro_busq.cedula=$("#repGastosPorCategoria_dni").val().replace(/ /g,'');
			filtro_busq.tipoConsulta=$("input[name='radio']:checked").val();

//SE REALIZA LA PETICION AL SERVICIO DE GASTOS POR CATEGORIA
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify({
				filtro_busq: filtro_busq
			})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {
				format: CryptoJSAesJson
			}).toString();
			$.post(baseURL + api + isoPais + "/reportes/gastosporcategorias",{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)})
			.done(function(response){
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
//SI LA CONSULTA ES SATISFACTORIA SE PROCEDE A LLENAR LA TABLA
				var tr;
				var td;
				var th;
				var trr;
				$("#repGastosPorCategoria_btnBuscar").show();
//SI EL CODIGO DE LA RESPUESTA ES 0 SE REALIZO LA PETICION CON EXITO Y TRAJO INFORMACION
				if(data.rc == "0"){

//SE LIMPIA PARTE DE LA PANTALLA EN CASO DE QUE HUBIESE OTRA CONSULTA
					$("#view-results").attr("style","");
					var tbody= $("#tbody-datos");
					$("#datos-cliente .1").remove();
					$("#datos-cliente-mes .1").remove();
					$("tbody td:not(.GC-long)").remove();
					$("#mensaje").remove();
					$(".dato").remove();

					$("#datos-cliente-mes").empty();
					$("#datos-cliente").empty();

					$("#datos-cliente-mes").append("<th>"+$("#cedula").attr("data")+data.dni+"</th>");
					$("#datos-cliente-mes").append("<th>"+$("#cuenta").attr("data")+data.tarjetaHabiente+"</th>");
					$("#datos-cliente-mes").append("<th>"+$("#rango").attr("data")+data.FechaInicial + " - " + data.FechaFinal+"</th>");


					$("#datos-cliente").append("<th>"+$("#cedula").attr("data")+data.dni+"</th>");
					$("#datos-cliente").append("<th>"+$("#cuenta").attr("data")+data.tarjetaHabiente+"</th>");
					$("#datos-cliente").append("<th>"+$("#rango").attr("data")+data.FechaInicial + " - " + data.FechaFinal+"</th>");



//SE INICIA LA CARGA DE LA GRAFICA
					$(".grafica").click(function(){

			    	var _axis="Bolivares";

//ARMADO DE JSON QUE USA KENDO.DATAVIZ PARA DIBUJAR LA GRAFICA
					var jsonChart={
						title:{
							text:$("#titulograficogc").attr("data") + $('option:selected',"#repGastosPorCategoria_producto").attr("des")
						},
						seriesDefaults: {
					         labels: {
					         template: "#= category # - #= kendo.format('{0:P}', percentage)#",
					         position: "outsideEnd",
					         visible: true,
					         background: "transparent",
					        }
					    },
						 legend: {
						   visible: true
						 },
						seriesDefaults: {
					        labels: {
					        visible: true,
					        format: $("#moneda").attr("data")+".{0}"
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
				          template: "#= category # - #= kendo.format('{0:P}', percentage) #",
				          color: "#FFFF",
				         }

					}

//SE CARGAN LAS CATEGORIAS DENTRO DEL JSON
					var datos = {};
					$.each(data.listaGrafico[0].categorias,function(posLista,itemLista){
						jsonChart.categoryAxis.categories.push(itemLista.nombreCategoria);
					});

//SE CARGAN LAS SERIES DENTRO DEL JSON
					var info = data
					$.each(info.listaGrafico[0].categorias,function(posLista,itemLista){
						$.each(info.listaGrafico[0].series,function(pos,item){
							datos = {};
							datos.category = itemLista.nombreCategoria;
							datos.value = item.valores[posLista];
							datos.color = colores[posLista];
							jsonChart.series[0].data.push(datos);
						});
					});

//MUESTRA EL JSON TERMINADO EN LA CONSOLA PARA VERIFICAR QUE TODO ESTA BIEN Y SE GENERA ..
//LA GRAFICA DENTRO DE UNA VENTANA MODAL

					$( "#chart" ).dialog({modal:true, width: 750, height: 400});
					$("#chart").kendoChart(jsonChart);
			  	    });

//SE VERIFICA SI EL RADIO ESTA ACTIVADO PARA ANUAL POR MES
					if($("#anual").is(":checked")){

//SE OCULTA EL GIF DE CARGANDO Y SE MUESTRA EL CONTENEDOR DE LA TABLA DE ANIO
					$('#cargando').fadeOut("slow");
					$('#div-anio').fadeIn("slow");
					$('#div-mes').fadeOut("slow");

//COMIENZA LA CARGA DE TABLA CON LOS VALORES DE CADA UNO DE LAS CATEGORIAS
						$.each(data.listaGrupo,function(posLista,itemLista){
							$.each(itemLista.gastoMensual,function(pos,item){
								if(item.mes == "ENERO"){
									tr=$("#enero");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}
								if(item.mes == "FEBRERO"){
									tr=$("#febrero");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "MARZO"){
									tr=$("#marzo");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "ABRIL"){
									tr=$("#abril");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "MAYO"){
									tr=$("#mayo");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "JUNIO"){
									tr=$("#junio");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "JULIO"){
									tr=$("#julio");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "AGOSTO"){
									tr=$("#agosto");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "SEPTIEMBRE"){
									tr=$("#septiembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "OCTUBRE"){
									tr=$("#octubre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "NOVIEMBRE"){
									tr=$("#noviembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

								if(item.mes == "DICIEMBRE"){
									tr=$("#diciembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","dato");
								}

							});

						tr=$("#totales");
						th=$(document.createElement("th")).appendTo(tr);
						th.html(itemLista.totalCategoria);
						th.attr("class","dato");

					});

//SE CARGAN LOS TOTATALES DE ACUERDO A CADA MES
					$.each(data.totalesAlMes,function(pos,item){
								if(item.mes == "ENERO"){
									tr=$("#enero");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}
								if(item.mes == "FEBRERO"){
									tr=$("#febrero");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "MARZO"){
									tr=$("#marzo");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "ABRIL"){
									tr=$("#abril");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "MAYO"){
									tr=$("#mayo");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "JUNIO"){
									tr=$("#junio");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "JULIO"){
									tr=$("#julio");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "AGOSTO"){
									tr=$("#agosto");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "SEPTIEMBRE"){
									tr=$("#septiembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "OCTUBRE"){
									tr=$("#octubre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "NOVIEMBRE"){
									tr=$("#noviembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

								if(item.mes == "DICIEMBRE"){
									tr=$("#diciembre");
									td=$(document.createElement("td")).appendTo(tr);
									td.html(item.monto);
									td.attr("class","GC-Medium dato");
								}

							});

							tr=$("#totales");
							th=$(document.createElement("th")).appendTo(tr);
							th.html(data.totalGeneral);
							th.attr("class","GC-Medium dato");

//EN CASO DE QUE QUE EL RADIO ESTA HABILITADO PARA MENSUAL
					}else{

//SE OCULTA EL GIF DE CARGANDO Y SE MUESTRA EL CONTENEDOR DE LA TABLA DE MES
						$('#cargando').fadeOut("slow");
						$('#div-mes').fadeIn("slow");
						$('#div-anio').fadeOut("slow");

//COMIENZA LA CARGA DE LOS DATOS DENTRO DE LA TABLA
						var tbody=$("#tbody-datos-mes");
						tbody.empty();
						var trr=$("#totales-mes");
						trr.empty();
						$.each(data.totalesPorDia,function(pos,item){
							tr=$(document.createElement("tr")).appendTo(tbody);
							td=$(document.createElement("td")).appendTo(tr);
							td.html(item.fechaDia);
							td.attr("class","GC-long");
							$.each(data.listaGrupo,function(Listapos,Listaitem){
								$.each(Listaitem.gastoDiario,function(posLista,itemLista){
									if(item.fechaDia == itemLista.fechaDia){
										td=$(document.createElement("td")).appendTo(tr);
										td.html(itemLista.monto);
									}
								});
							});
							td=$(document.createElement("td")).appendTo(tr);
							td.html(item.monto);
							td.attr("class","GC-Medium");
						});

						th=$(document.createElement("th")).appendTo(trr);
						th.html("Totales");
						th.attr("class","GC-long");
						$.each(data.listaGrupo,function(Listapos,Listaitem){
							th=$(document.createElement("th")).appendTo(trr);
							th.html(Listaitem.totalCategoria);
						});

						th=$(document.createElement("th")).appendTo(trr);
						th.html(data.totalGeneral);
						th.attr("class","GC-Medium");


					}

//EN CASO DE QUE EL SERVICIO NO DEVUELVA 0 SE MUESTRA EL MENSAJE CORRESPONDIENTE AL CODIGO
				}else{
					if(data.rc =="-29"){
			             alert("Usuario actualmente desconectado");
			             $(location).attr('href',baseURL+isoPais+'/login');
			         }else{

						$("#mensaje").remove();
						var contenedor = $("#div_tablaDetalle");
						$('#cargando').fadeOut("slow");
						contenedor.html('<div id="top-batchs"><span aria-hidden="true" class="icon" data-icon="&#xe046;"></span> Gastos por categoría </div>');
						$("#div-mes").fadeOut("fast");
						$("#div-anio").fadeOut("fast");
			 			contenedor.fadeIn("fast");
			 			var div =$(document.createElement("div")).appendTo(contenedor);
			 			div.attr("id","mensaje");
			 			div.attr("style","background-color:rgb(252,199,199); margin-top:63px;");
			 			var p = $(document.createElement("p")).appendTo(div);
			 			p.html(data.mensaje);
			 			p.attr("style","text-align:center;padding:10px;font-size:14px");
			 		}
				}
			});
		}
	});

//---------------------------------------------------------
//FUNCION PARA VALIDAR CADA UNO DE LOS TIPOS DE INPUT
//---------------------------------------------------------
	function validar_filtro_busqueda(div){
		var valido=true;
		//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		$.each($("#"+div+" input[type='text'].required"),function(posItem,item){
			var $elem=$(item);
			if($elem.val()==""){
				if($("#repGastosPorCategoria_tarjeta").val().replace(/ /g,'') == "" && $("#repGastosPorCategoria_dni").val().replace(/ /g,'')==""){
					valido=false;
					$elem.attr("style","border-color:red");
				}
				else{
					$("#repGastosPorCategoria_tarjeta").attr("style","");
					$("#repGastosPorCategoria_dni").attr("style","");
				}
				if($("#mensual").is(":checked")){
					if ($("#repGastosPorCategoria_fecha_fin").val() == "" || $("#repGastosPorCategoria_fecha_ini").val()=="") {
						valido=false;
						$("#repGastosPorCategoria_fecha_fin").attr("style","border-color:red");
						$("#repGastosPorCategoria_fecha_ini").attr("style","border-color:red");
					}
				}else{
					$("#repGastosPorCategoria_fecha_fin").attr("style","");
					$("#repGastosPorCategoria_fecha_ini").attr("style","");
				}
			}else{
				$elem.attr("style","");
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
			$("#mensajeError").fadeIn("fast");
		}else{
			$("#mensajeError").fadeOut("fast");
		}


		return valido;
}

  function descargarArchivo(datos, url, titulo){

  $aux = $("#cargando").dialog({title:titulo,modal:true, close:function(){$(this).dialog('close')}, resizable:false });

	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		datos.ceo_name = ceo_cook

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

//------------------
//FIN DEL DOCUMENTO
//------------------
});
