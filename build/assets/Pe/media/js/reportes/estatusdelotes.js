$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});

$(document).ready(function() {

		$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function( data ) {
			$("#cargando_empresa").fadeOut("slow");
			if(!(data.ERROR)){

				$.each(data.lista, function(k,v){

					$("#EstatusLotes-empresa").append('<option value="'+v.accodcia+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" acrif="'+v.acrif+'">'+v.acnomcia+'</option>');
				});
			}else{
				if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
		         	$("#EstatusLotes-empresa").append('<option value="" >'+data.ERROR+'</option>');
		         }
				}
		});

		$("#EstatusLotes-empresa").on("change",function(){

			acrif = $('option:selected', this).attr("acrif");

			if(acrif){

			$("#EstatusLotes-producto").children( 'option:not(:first)' ).remove();

			$("#cargando_producto").fadeIn("slow");
			$(this).attr('disabled',true);
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$.post(baseURL + api + isoPais + "/producto/lista", { 'acrif': acrif, ceo_name: ceo_cook }, function(data){
				$("#cargando_producto").fadeOut("slow");
				$("#EstatusLotes-empresa").removeAttr('disabled');
				if(!data.ERROR){
					$.each(data, function(k,v){
						$("#EstatusLotes-producto").append('<option value="'+v.idProducto+'" >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
					});
				}else{
					if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
					$("#EstatusLotes-producto").append('<option value="">Empresa sin productos</option>');
				}
				}

			});
		}
		});




		$("#export_excel").click(function(){

			/*datos = {
				empresa: filtro_busq.empresa,
				fechaInicial: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				lotes_producto: filtro_busq.lotes_producto,
				paginaActual: 1
			}
			descargarArchivo(datos, baseURL+api+isoPais+"/reportes/estatuslotesExpXLS", "Exportar Excel" );
*/
			$('form#formulario').empty();
	$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
	$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
	$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
	$('form#formulario').append('<input type="hidden" name="lotes_producto" value="'+filtro_busq.lotes_producto+'" />');
	$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
	$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/estatuslotesExpXLS");
	$('form#formulario').submit();
		});

$("#export_pdf").click(function(){

	/*datos = {
		empresa: filtro_busq.empresa,
		fechaInicial: filtro_busq.fechaInicial,
		fechaFin: filtro_busq.fechaFin,
		lotes_producto: filtro_busq.lotes_producto,
		paginaActual: 1
	}
	descargarArchivo(datos, baseURL+api+isoPais+"/reportes/estatuslotesExpPDF", "Exportar PDF" );
*/
$('form#formulario').empty();
	$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
	$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
	$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
	$('form#formulario').append('<input type="hidden" name="lotes_producto" value="'+filtro_busq.lotes_producto+'" />');
	$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
	$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/estatuslotesExpPDF");
	$('form#formulario').submit();
});

//METODO PARA REALIZAR LA BUSQUEDA
var filtro_busq={};
	$("#EstatusLotes-btnBuscar").click(function(){

		var $consulta;
		filtro_busq.empresa=$("#EstatusLotes-empresa").val();
		filtro_busq.fechaInicial=$("#EstatusLotes-fecha-in").val();
		filtro_busq.fechaFin=$("#EstatusLotes-fecha-fin").val();
		filtro_busq.lotes_producto=$("#EstatusLotes-producto").val();
		filtro_busq.paginaActual=1;
		if(validar_filtro_busqueda("lotes-2")){
			$('#cargando').fadeIn("slow");
			$("#EstatusLotes-btnBuscar").hide();
	    	$('#div_tablaDetalle').fadeOut("fast");

			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			filtro_busq.ceo_name = ceo_cook;
			$consulta = $.post(baseURL + api + isoPais + "/reportes/estatuslotes",filtro_busq );
			$consulta.done(function(data){
				$("#mensaje").remove();
				$('#cargando').fadeOut("slow");
				$("#EstatusLotes-btnBuscar").show();
				$("#div_tablaDetalle").fadeIn("slow");
				var tbody=$("#tbody-datos-general");
				tbody.empty();
				var tr;
				var td;

				if($(".tbody-statuslotes").hasClass('dataTable')){
          			$('.tbody-statuslotes').dataTable().fnClearTable();
          			$('.tbody-statuslotes').dataTable().fnDestroy();
        		}
				if(data.rc == "0"){
					$("#view-results").attr("style","");
					$("#tabla-estatus-lotes").fadeIn("fast");
					$.each(data.lista,function(posLista,itemLista){
						tr=$(document.createElement("tr")).appendTo(tbody);
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.acnombre);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.acnumlote);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.status);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.dtfechorcarga);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.dtfechorvalor);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.ncantregs);
						td.attr("style","text-align: center");
						td=$(document.createElement("td")).appendTo(tr);
						td.html(itemLista.nmonto);
						td.attr("style","text-align: center");
					});
				$('#tabla-estatus-lotes tbody tr:even').addClass('even ');

				paginar();
				}else{
					if(data.rc =="-29"){
			             alert(data.mensaje);
			             $(location).attr('href',baseURL+isoPais+'/login');
			         }else{
		 				$("#mensaje").remove();
		 				var contenedor=$("#div_tablaDetalle");
		 				$("#tabla-estatus-lotes").fadeOut("fast");
			 			$("#view-results").attr("style","display:none");
			 			var div =$(document.createElement("div")).appendTo(contenedor);
			 			div.attr("id","mensaje");
			 			div.attr("style","background-color:rgb(252,199,199); margin-top:45px;");
			 			var p = $(document.createElement("p")).appendTo(div);
			 			p.html(data.mensaje);
			 			p.attr("style","text-align:center;padding:10px;font-size:14px");
			 		}
				}
			});

}
});


function validar_filtro_busqueda(div){
	var valido=true;
//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
$.each($("#"+div+" input[type='text'].required"),function(posItem,item){
	var $elem=$(item);
	if($elem.val()==""){
		valido=false;
		$elem.attr("style","border-color:red");
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
	$("#mensajeError").html("Por favor rellene los campos marcados en color rojo");
	$("#mensajeError").fadeIn("fast");
}else{
	$("#mensajeError").fadeOut("fast");
}


return valido;
}

function CalculateDateDiff(dateFrom, dateTo) {

	var dateT=new Date(parseInt(dateTo.split("/")[2]),parseInt(dateTo.split("/")[1]),parseInt(dateTo.split("/")[0]));
	var dateF=new Date(parseInt(dateFrom.split("/")[2]),parseInt(dateFrom.split("/")[1]),parseInt(dateFrom.split("/")[0]));
	var difference = (dateT - dateF);

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

	if(years> 0){
		$("#mensajeError").html("El rango de fecha no debe ser mayor a 3 meses");
		$("#mensajeError").fadeIn("fast");
		return true;
	}
	if(months<3){
		$("#mensajeError").fadeOut("fast");
		return false;
	}else{
		$("#mensajeError").html("El rango de fecha no debe ser mayor a 3 meses");
		$("#mensajeError").fadeIn("fast");
	}


	return true;
}


function downloadme(x){
	myTempWindow = window.open(x,'','left=1000,screenX=1000');
	myTempWindow.document.execCommand('SaveAs','null','download.pdf');
}

function paginar(){
$(".tbody-statuslotes").dataTable( {
          "iDisplayLength": 10,
          'bDestroy':true,
          "sPaginationType": "full_numbers",
          "bLengthChange": false,
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
         } );
}




	calendario( "EstatusLotes-fecha-in" );
	calendario( "EstatusLotes-fecha-fin");


	function calendario(input){

		$("#"+input).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
        	dateFormat:"dd/mm/yy",
			maxDate: "+0D",
			onClose: function(selectedate){
				if(input=='EstatusLotes-fecha-in' && selectedate){
					$("#EstatusLotes-fecha-fin").datepicker('option','minDate',selectedate);
				}else if(input=='EstatusLotes-fecha-in'){
					$("#EstatusLotes-fecha-fin").datepicker('option','minDate',"");
				}
				if(input=='EstatusLotes-fecha-fin' && selectedate){
					$("#EstatusLotes-fecha-in").datepicker('option','maxDate',selectedate);
				}else if(input=='EstatusLotes-fecha-fin'){
					$("#EstatusLotes-fecha-in").datepicker('option','maxDate',"+0D");
				}
			}
		});
	}

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
