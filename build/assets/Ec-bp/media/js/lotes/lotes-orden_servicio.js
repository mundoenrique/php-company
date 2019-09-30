$(function () {

	var empty = $('#empty').val()
	if (empty != 'nonEmpty') {
		$("<div><h3>Existe uno más lotes sin retenciones asociadas</h3><h5>" + empty + "</h5></div>").dialog({

			dialogClass: "hide-close",
			title: "Retenciones",
			modal: true,
			resizable: false,
			buttons: {
				"Cancelar": { text: 'Cancelar', class: 'novo-btn-secondary-modal',
				click: function () {
				$(this).dialog("close");
				$('#confirmarPreOSL').show();
				$('#cancelar-OS').show();}
			},
			"continuar": { text: 'continuar', class: 'novo-btn-primary-modal',
				click: function () {
					$(this).dialog('destroy');
					$('#confirmarPreOSL').show();
					$('#cancelar-OS').show();
				}
			}
			}
		});
	} else {
		$('#confirmarPreOSL').show();
		$('#cancelar-OS').show();
	}

	$('#lotes-general').show();


	//$("#tabla-datos-general").find(".OSinfo").hide(); // ocultar lotes de os


	// BOTON CONFIRMAR -- LLAMA ORDEN DE SERVICIO

	$("#confirmarPreOSL").on("click", function () {
		var l = $("#tempIdOrdenL").val();
		var lnf = $("#tempIdOrdenLNF").val();
		var rePassOtp = $("#passOtp").val();

		if(rePassOtp == 0 && rePassOtp !== ''){
			notificacion("Confirmar Codigo de seguridad", "No puede llevar espacios en blanco", null);
			return false;
		}

		$aux = $('#loading').dialog({
			dialogClass: "hide-close",
			title: 'Confirmar cálculo orden de servicio',
			modal: true,
			resizable: false
		});
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);


		var dataRequest = JSON.stringify({
			tempIdOrdenL: l,
			tempIdOrdenLNF: lnf,
			autorizacionOtp: rePassOtp
		})

		dataRequest  = CryptoJS.AES.encrypt(dataRequest , ceo_cook, {format: CryptoJSAesJson}).toString();

		$.post(baseURL + api + isoPais + "/lotes/confirmarPreOSL", {
				request: dataRequest,
				ceo_name: ceo_cook,
				plot: btoa(ceo_cook)
			})
			.done(function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
					format: CryptoJSAesJson
				}).toString(CryptoJS.enc.Utf8))
				$aux.dialog('destroy');

				if (!data.ERROR) {
					if (data.moduloOS) {
						$("#data-confirm").attr('value', data.ordenes);
						ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						$('#toOS').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
						notificacion("Confirmar cálculo orden de servicio", "<h3>Proceso exitoso</h3>", "form#toOS");
					} else {
						ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						$('#viewAutorizar').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
						notificacion("Confirmar cálculo orden de servicio", "<h3>Proceso exitoso</h3>", "#viewAutorizar");
					}

				} else {

					if (data.ERROR == '-29') {
						alert('Usuario actualmente desconectado');
						location.reload();
					} else if (data.ERROR == '-56') {
						notificacion("Error de facturación", data.msg, null);
					} else {
						notificacion("Confirmar cálculo orden de servicio", data.ERROR, null);
					}
					//notificacion("Confirmar cálculo orden de servicio",data.ERROR,null);
				}

			});

	});

	function validationIn(emailtcs){




	}


	// MOSTRAR/OCULTAR LOTES SEGUN OS

	$("#tabla-datos-general").on("click", "#ver_lotes", function () {

		var OS = $(this).parents("tr").attr('id');
		var $lotes = $("#tabla-datos-general").find("." + OS);

		$lotes.is(":visible") ? $lotes.fadeOut("slow") : $lotes.fadeIn("slow");
		$('.OSinfo').not("." + OS).hide();
	});

	$("#tabla-datos-general").on("click", ".viewLo", function () {

		var idLote = $(this).attr('id');

		$('form#detalle_lote').append('<input type="hidden" name="data-lote" value="' + idLote + '" />');
		$("#detalle_lote").submit();

	});


	function notificacion(titulo, mensaje, sitio) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({
			dialogClass: "hide-close",
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			bgiframe: true,
			close: function () {
				$(this).dialog("destroy");
				if (sitio) {
					$(sitio).submit();
				}
			},
			buttons: {
				"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
						$(this).dialog("destroy");
						if (sitio) {
							$(sitio).submit();
						}
					}
				}
			}
		});
	}

	$('#cancelar-OS').on('click', function () {
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
