
$(document).ready(function() {
	var path =window.location.href.split( '/' );
	var base = path[0]+ "//" +path[2]+'/'+path[3];
	var pais = path[4];
	var api = "/api/v1/";
	var scroll_interval;
	var ancho=0;

	$(".fecha").keypress(function(e){
		if(e.keycode != 8 || e.keycode != 46){
			return false;
		}
	});

	var fecha = new Date();
	fecha = fecha.getFullYear();
	var i=0;
	var anio;
	do{
		anio= parseInt(fecha)-i;
		    $("#anio").append('<option value="'+anio.toString()+'">'+anio.toString()+'</option>');
		i=i+1;
	}while(i!=20);


	Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		return {
			radialGradient: { cx: 0.9, cy: 0.9, r: 0.9 },
			stops: [
			[0, color],
            [1, Highcharts.Color(color).brighten(0.2).get('rgb')] // darken
            ]
        };
    });


	$("#cargando_empresa").fadeIn("slow");
	$.getJSON(base + api + pais + '/empresas/lista').always(function( data ) {
		$("#cargando_empresa").fadeOut("slow");
		if(!(data.ERROR)){

			$.each(data.lista, function(k,v){

				$("#repUsuario_empresa").append('<option accodcia="'+v.accodcia+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" value="'+v.acrif+'">'+v.acnomcia+'</option>');
			});
		}else{
			if(data.ERROR.indexOf('-29') !=-1){
				alert("Usuario actualmente desconectado");
				$(location).attr('href',base+'/'+pais+'/login');
			}else{
				$("#repUsuario_empresa").append('<option value="">'+data.ERROR+'</option>');
			}
		}
	});


	$.each($(".radio"),function(pos,item){
		$(item).click(function(){
			if($(this).is(":checked")){
				if($(this).val()!="0"){
					$( "#repUsuario_fechaInicial" ).attr("disabled","true");
					$( "#repUsuario_fechaFinal" ).attr("disabled","true");
				}else{
					$( "#repUsuario_fechaInicial" ).removeAttr("disabled");
					$( "#repUsuario_fechaFinal" ).removeAttr("disabled");			
				}
			}
		});
	});

	

//SE USA EL DATEPICKER PARA FACILITAR EL CONTROL DE LA SELECCION DE FECHA, 
//YA VALIDA QUE UNA FECHA INICIO NO SEA MAYOR QUE LA FECHA FIN
$( "#repUsuario_fechaInicial" ).datepicker({
	defaultDate: "+1w",
	changeMonth: true,
	changeYear: true,
	dateFormat:"dd/mm/yy",
	numberOfMonths: 1,
	maxDate: "+0D",
	onClose: function( selectedDate ) {
		if(selectedDate){
			$( "#repUsuario_fechaFinal" ).datepicker( "option", "minDate", selectedDate );
		}else{
			$( "#repUsuario_fechaFinal" ).datepicker( "option", "minDate", '' );
		}
		
	}
});


$( "#repUsuario_fechaFinal" ).datepicker({
	defaultDate: "+1w",
	dateFormat:"dd/mm/yy",
	changeMonth: true,
	changeYear: true,
	numberOfMonths: 1,
	maxDate: "+0D",
	onClose: function( selectedDate ) {
		if(selectedDate){
			$( "#repUsuario_fechaInicial" ).datepicker( "option", "maxDate", selectedDate );
		}else{
			$( "#repUsuario_fechaInicial" ).datepicker( "option", "maxDate", "+0D" );
		}

	}
});

$("#exportXLS_a").click(function(){

	
		/*datos={
			empresa: filtro_busq.empresa,
			fechaInicial: filtro_busq.fechaInicial,
			fechaFin: filtro_busq.fechaFin,
			filtroFecha: filtro_busq.filtroFecha,
			producto: filtro_busq.producto,
			nomEmpresa: filtro_busq.acnomcia,
			descProd: filtro_busq.productoDES +"/"+filtro_busq.marca
		}	 	 

		descargarArchivo(datos, base+api+pais+"/reportes/cuentaConcentradoraExpXLS", "Exportar Excel" );
*/

		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
		$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
		$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
		$('form#formulario').append('<input type="hidden" name="filtroFecha" value="'+filtro_busq.filtroFecha+'" />');
		$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
		$('form#formulario').attr('action',base+api+pais+"/reportes/cuentaConcentradoraExpXLS");
		$('form#formulario').submit(); 

	});


$("#export_excel").on("click", function(){ // descargar orden de servicio

			$aux = $("#loading").dialog({title:'Descargando archivo Excel',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			$('form#formulario').empty();	
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
			$('form#formulario').append('<input type="hidden" name="filtroFecha" value="'+filtro_busq.filtroFecha+'" />');
			$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
			$('form#formulario').attr('action',base+api+pais+"/reportes/cuentaConcentradoraExpXLS");
    		$('form#formulario').submit(); 
    		setTimeout(function(){$aux.dialog('destroy')},8000);

});



$("#exportPDF_a").on("click", function(){ // descargar orden de servicio
		
			$aux = $("#loading").dialog({title:'Descargando archivo Excel',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
			$('form#formulario').append('<input type="hidden" name="filtroFecha" value="'+filtro_busq.filtroFecha+'" />');
			$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
			$('form#formulario').attr('action',base+api+pais+"/reportes/cuentaConcentradoraExpPDF");
    		setTimeout(function(){$aux.dialog('destroy')},8000);

});


// $("#exportPDF_a").click(function(){
			
// 		/*datos={
// 			empresa: filtro_busq.empresa,
// 			fechaInicial: filtro_busq.fechaInicial,
// 			fechaFin: filtro_busq.fechaFin,
// 			filtroFecha: filtro_busq.filtroFecha,
// 			producto: filtro_busq.producto,
// 			nomEmpresa: filtro_busq.acnomcia,
// 			descProd: filtro_busq.productoDES +"/"+filtro_busq.marca
// 		}	 	 

// 		descargarArchivo(datos, base+api+pais+"/reportes/cuentaConcentradoraExpPDF", "Exportar PDF" );
// */		
// $('form#formulario').empty();
// 		$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
// 		$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
// 		$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
// 		$('form#formulario').append('<input type="hidden" name="filtroFecha" value="'+filtro_busq.filtroFecha+'" />');
// 		$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
// 		$('form#formulario').attr('action',base+api+pais+"/reportes/cuentaConcentradoraExpPDF");
// 		$('form#formulario').submit();

// 	});

$(".consolidado").click(function(){
	var formato;
	if($(this).hasClass("xls")){
		formato="XLS";
	}else{
		formato="PDF";
	}

	$("#consolid").dialog({
		modal:true, 
		resizable: true,
		title:"Selección de Año",
		width:"180px",
		maxheight:"250px",
		buttons:{
			OK:function(){
				
				$(".ui-dialog-content").dialog().dialog("close");
				/*datos={
					empresa: filtro_busq.empresa,
					fechaInicial: filtro_busq.fechaInicial,
					fechaFin: filtro_busq.fechaFin,
					filtroFecha: filtro_busq.filtroFecha,
					producto: filtro_busq.producto,
					nomEmpresa: filtro_busq.acnomcia,
					descProd: filtro_busq.productoDES +"/"+filtro_busq.marca,
					anio: $("option:selected","#anio").val()
				}	 	 

				descargarArchivo(datos, base+api+pais+"/reportes/cuentaConcentradoraConsolidadoExp"+formato, "Exportar "+formato );
			*/
			$('form#formulario').empty();
					$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
					$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
					$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
					$('form#formulario').append('<input type="hidden" name="filtroFecha" value="'+filtro_busq.filtroFecha+'" />');
					$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
					$('form#formulario').append('<input type="hidden" name="anio" value="'+$("option:selected","#anio").val()+'" />');
					$('form#formulario').attr('action',base+api+pais+"/reportes/cuentaConcentradoraConsolidadoExp"+formato);
					$('form#formulario').submit(); 
			}
		}
	});
});

$("#grafic").click(function(){

//	var filtro_busq={};
var $consulta;


$('#cargando').dialog({ modal: true,maxWidth: 700,maxHeight: 300,dialogClass: 'hide-close' });


		$consulta = $.post(base + api + pais + "/reportes/graficoCuentaConcentradora",filtro_busq );
		$consulta.done(function(data){
			$(".ui-dialog-content").dialog().dialog("close");
			if(data.rc==0){

				
				$('#grafica').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: $('option:selected',"#repUsuario_empresa").attr("acnomcia")
					},
					
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true
							},
							showInLegend: true
						}
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}% = {point.y:.1f}</b>'
					},
					series: [{
						type: 'pie',
						name: 'Operaciones',
						data: [ ["Débitos",parseFloat( data.totalDebitos)],["Créditos",parseFloat(data.totalCreditos)]]

					}]
				});

				$( "#grafica" ).dialog({modal:true, width: 800, height: 400});
				$("#grafica").height(400);
				$("#grafica svg").height(385);
			}else{
				notificacion('Gráfica cuenta concentradora',data.mensaje);
			}


		});

});


//METODO PARA REALIZAR LA BUSQUEDA 
$("#repUsuario_btnBuscar").click(function(){ evBuscar=true; BuscarDepositos(0)});





function validar_filtro_busqueda(div){
	var valido=true;
//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS 
$.each($("#"+div+" input[type='text'].required"),function(posItem,item){
	var $elem=$(item);
	if(!$elem.hasClass("bloqued")){
		if($elem.val()=="" && $('#rango').is(':checked')){
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


function CalculateDateDiff(dateFrom, dateTo) {
	var difference = (dateTo - dateFrom);

	var years = Math.floor(difference / (1000 * 60 * 60 * 24 * 365));
	difference -= years * (1000 * 60 * 60 * 24 * 365);
	var months = Math.floor(difference / (1000 * 60 * 60 * 24 * 30.4375));

	var dif = '';
	if (years > 0)
		dif = years + ' años ';

	if (months > 0) {
		if (years > 0) dif += ' y ';
		dif += months + ' meses';
	}

	if(months>3)
		return false;

	return true;
}
var filtro_busq={};
function BuscarDepositos(paginaActual) {
	var d1 = $('#repUsuario_fechaInicial').val().split("/");
	var dat1 = new Date(d1[2], parseFloat(d1[1])-1, parseFloat(d1[0]));
	var d2 = $('#repUsuario_fechaFinal').val().split("/");
	var dat2 = new Date(d2[2], parseFloat(d2[1])-1, parseFloat(d2[0]));
	
	var $consulta;
	var fecha = new Date();

	if (paginaActual == 0 ){
		paginaActual = 1;
	}


	filtro_busq.paginaActual=paginaActual;

	if(evBuscar){
		if(validar_filtro_busqueda("lotes-2")){

			filtro_busq.empresa=$("#repUsuario_empresa").val();
			filtro_busq.fechaInicial=$("#repUsuario_fechaInicial").val();
			filtro_busq.fechaFin=$("#repUsuario_fechaFinal").val();		
			filtro_busq.filtroFecha=$("input[name='radio']:checked").val();
			
			filtro_busq.tipoNota="";
			if($("#cargo").is(":checked"))
				{filtro_busq.tipoNota=$("#cargo").val()}
			else if($("#abono").is(":checked"))
				{filtro_busq.tipoNota=$("#abono").val()}
			if($("#abono").is(":checked") && $("#cargo").is(":checked"))
				{filtro_busq.tipoNota=""}



			filtro_busq.acnomcia = $('option:selected', "#repUsuario_empresa").attr("acnomcia");
			filtro_busq.acrif = $('option:selected', "#repUsuario_empresa").attr("value");

			WS(filtro_busq);
		}

	}else{	    		
		WS(filtro_busq);
	}
}


function WS(filtro_busq){
	$('#cargando').fadeIn("slow");
	$("#repUsuario_btnBuscar").hide();
	$('#div_tablaDetalle').hide();
			//SE REALIZA LA INVOCACION AJAX
			$consulta = $.post(base + api + pais + "/reportes/cuentaConcentradora",filtro_busq );
			//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"
			$consulta.done(function(data){
				$("#mensaje").remove();
				$("#div_tablaDetalle").fadeIn("slow");
				
				//$("#paginacion").show();
				$("#contend-pagination").show();
				$('#cargando').fadeOut("slow");
				$("#repUsuario_btnBuscar").show();
				contenedor=$("#div_tablaDetalle");
				contenedor.fadeIn("slow");
				var tbody=$("#tbody-datos-general");
				if (evBuscar) {
					tbody.empty();
				}
				var tr;
				var td;
				
			//DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA... 
			//DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda"
			if(data.rc == "0"){ 
				$("#tabla-datos-general").fadeIn("slow");
				$("#view-results").attr("style","");

				$.each(data.depositoGMO.lista,function(posLista,itemLista){
					tr=$(document.createElement("tr")).appendTo(tbody);
					tr.addClass('pg'+data.paginaActual);
					td=$(document.createElement("td")).appendTo(tr);
					td.html(itemLista.fechaRegDep);
					td=$(document.createElement("td")).appendTo(tr);
					td.html(itemLista.descripcion);
					td=$(document.createElement("td")).appendTo(tr);
					td.html(itemLista.referencia);
					if(itemLista.tipoNota=="D"){
						td=$(document.createElement("td")).appendTo(tr);
						td.html(" - "+itemLista.montoDeposito);	
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html("");
					}else if(itemLista.tipoNota=="C"){
						td=$(document.createElement("td")).appendTo(tr);
						td.html("");	
						td=$(document.createElement("td")).appendTo(tr);
						td.html(" + " + itemLista.montoDeposito);
						td.attr("style","text-align: center");
					}
					td=$(document.createElement("td")).appendTo(tr);
					td.html(itemLista.saldoDisponible);
					td.attr("style","text-align: center");
				});

		paginacion(data.totalPaginas, data.paginaActual);

		// PAGINACION
		/*
		if (evBuscar) {
			paginar(data.totalPaginas, data.paginaActual);
			evBuscar=false;
		}
		*/

		$('#tabla-datos-general tbody tr:even').addClass('even ');  

	}else{
		if(data.rc =="-29"){
			alert("Usuario actualmente desconectado");
			$(location).attr('href',base+'/'+pais+'/login');
		}else{
			//$("#paginacion").hide();
			$("#contend-pagination").hide();
			$("#mensaje").remove();
			$("#tabla-datos-general").fadeOut("fast");
			$("#view-results").attr("style","display:none");
			var div =$(document.createElement("div")).appendTo(contenedor);
			div.attr("id","mensaje");
			div.attr("style","background-color:rgb(252,199,199); margin-top:43px;");
			var p = $(document.createElement("p")).appendTo(div);
			p.html(data.mensaje);
			p.attr("style","text-align:center;padding:10px;font-size:14px");

		}
	}

});
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
			if( !$('.tbody-CC tr').hasClass('pg'+page) ){
				BuscarDepositos(page);
			}
			$('.tbody-CC .tbody-reportes tr').hide();
			$('.tbody-CC .pg'+page).show();

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
			BuscarDepositos(id[1]);
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
			BuscarDepositos(1);
		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22").click(function(){
			BuscarDepositos(total);
		});
	}
/***********************Paginacion fin***********************/

function descargarArchivo(datos, url, titulo){

	$aux = $("#cargando").dialog({title:titulo,modal:true, close:function(){$(this).dialog('close')}, resizable:false });

	$.post(url,datos).done(function(data){
		$aux.dialog('destroy')
		if(!data.ERROR){
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="bytes" value="'+JSON.stringify(data.bytes)+'" />');       
			$('form#formulario').append('<input type="hidden" name="ext" value="'+data.ext+'" />');  
			$('form#formulario').append('<input type="hidden" name="nombreArchivo" value="'+data.nombreArchivo+'" />');  
			$('form#formulario').attr('action',base+'/'+pais+"/file");
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