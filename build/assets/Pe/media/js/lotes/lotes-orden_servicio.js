var authloading=false;

$(window).bind('beforeunload', function(e) {
	if(authloading===true){
		//return "Unloading this page may lose. What do you want to do..."
		return "En este momento se esta procesando una petición, por favor espere, de lo contrario podría usted perder información considerada como importante.";
		e.preventDefault();
	}
});

$(function(){

	var empty = $('#empty').val()
	if (empty != 'nonEmpty') {
		$("<div><h3>Existe uno más lotes sin retenciones asociadas</h3><h5>" + empty + "</h5></div>").dialog({
			title:"Retenciones",
			modal:true,
			resizable:false,
			close: function(){
				$(this).dialog('destroy');
				$('#confirmarPreOSL').show();
				$('#cancelar-OS').show();
			},
			buttons:{
				continuar: function () {
					$(this).dialog('destroy');
					$('#confirmarPreOSL').show();
					$('#cancelar-OS').show();
				}
			}
		});
	} else{
		$('#confirmarPreOSL').show();
		$('#cancelar-OS').show();
	}

	$('#lotes-general').show();

	//$("#tabla-datos-general").find(".OSinfo").hide(); // ocultar lotes de os


	// BOTON CONFIRMAR -- LLAMA ORDEN DE SERVICIO

	$("#confirmarPreOSL").on("click",function(){
		var l = $("#tempIdOrdenL").val();
		var lnf = $("#tempIdOrdenLNF").val();

		authloading=true;

		$aux = $('#loading').dialog({title:'Confirmar cálculo orden de servicio',close: function(){$(this).dialog('close');}, modal: true, resizable:false});
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var dataRequest = JSON.stringify({"tempIdOrdenL": l, "tempIdOrdenLNF": lnf});
		dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
		$.post(baseURL+api+isoPais+"/lotes/confirmarPreOSL", {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)})
			.done(function(response) {
				var data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
				$aux.dialog('destroy');

				authloading=false;

				if(!data.ERROR){
					if(data.moduloOS){
						$("#data-confirm").attr('value',data.ordenes);
						notificacion("Confirmar cálculo orden de servicio","<h3>Proceso exitoso</h3>","form#toOS");
					}else{
						notificacion("Confirmar cálculo orden de servicio","<h3>Proceso exitoso</h3><h5>No tiene permitido gestionar ordenes de servicio.</h5>","#viewAutorizar");
					}

				}else{

					if(data.ERROR=='-29'){
						alert('Usuario actualmente desconectado'); location.reload();
					}else if(data.ERROR=='-56'){
						notificacion("Error de facturación",data.msg,null);
					}else{
						notificacion("Confirmar cálculo orden de servicio",data.ERROR,null);
					}
					//notificacion("Confirmar cálculo orden de servicio",data.ERROR,null);
				}

			});

	});


	// MOSTRAR/OCULTAR LOTES SEGUN OS

	$("#tabla-datos-general").on("click","#ver_lotes", function(){

		var OS = $(this).parents("tr").attr('id');
		var $lotes = $("#tabla-datos-general").find("."+OS);

		$lotes.is(":visible") ? $lotes.fadeOut("slow") : $lotes.fadeIn("slow");
		$('.OSinfo').not("."+OS).hide();
	});

	$("#tabla-datos-general").on("click",".viewLo", function(){

		var idLote = $(this).attr('id');

		$('form#detalle_lote').append('<input type="hidden" name="data-lote" value="'+idLote+'" />');
		$("#detalle_lote").submit();

	});


	function notificacion(titulo, mensaje, sitio){

		var canvas = "<div>"+mensaje+"</div>";

		$(canvas).dialog({
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			bgiframe: true,
			close: function(){
				$(this).dialog("destroy");
				if(sitio){
					$(sitio).submit();
				}
			},
			buttons: {
				OK: function(){
					$(this).dialog("destroy");
					if(sitio){
						$(sitio).submit();
					}
				}
			}
		});
	}

	$('#cancelar-OS').on('click', function() {
		$('form#viewAutorizar').append($('#tempIdOrdenL'));
		$("#viewAutorizar").submit();

	});

	// var paginar = function($tabla){
	// 	var tabla = $tabla.dataTable( {
	//          "iDisplayLength": 10,
	//          'bDestroy':true,
	//          "sPaginationType": "full_numbers",
	//          "oLanguage": {
	//            "sProcessing":     "Procesando...",
	//            "sLengthMenu":     "Mostrar _MENU_ registros",
	//            "sZeroRecords":    "No se encontraron resultados",
	//            "sEmptyTable":     "Ningún dato disponible en esta tabla",
	//            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	//            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
	//            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	//            "sInfoPostFix":    "",
	//            "sSearch":         "Buscar:",
	//            "sUrl":            "",
	//            "sInfoThousands":  ",",
	//            "sLoadingRecords": "Cargando...",
	//            "oPaginate": {
	//                "sFirst":    "<<",
	//                "sLast":     ">>",
	//                "sNext":     ">",
	//                "sPrevious": "<"
	//            }
	//          }
	//         } );
	// 	return tabla;
	// }

//	paginar($('#tabla-datos-general'));
//	paginar($('#tablelotesNF'));


}); // fin document ready
