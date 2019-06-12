$(function () {

	var empty = $('#empty').val()
	if (empty != 'nonEmpty') {
		$("<div><h3>Existe uno más lotes sin retenciones asociadas</h3><h5>" + empty + "</h5></div>").dialog({
			title: "Retenciones",
			modal: true,
			resizable: false,
			draggable: false,
			dialogClass: 'hide-close',
			close: function () {
				$(this).dialog('destroy');
				$('#confirmarPreOSL').show();
				$('#cancelar-OS').show();
			},
			buttons: {
				Continuar: function () {
					$(this).dialog('destroy');
					$('#confirmarPreOSL').show();
					$('#cancelar-OS').show();
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
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$aux = $('#loading').dialog({
			title: 'Confirmar cálculo orden de servicio',
			modal: true,
			resizable: false,
			draggable: false,
			dialogClass: 'hide-close'
		});
		var dataRequest = JSON.stringify({
			tempIdOrdenL: l,
			tempIdOrdenLNF: lnf
		})
		dataRequest  = CryptoJS.AES.encrypt(dataRequest , ceo_cook, {format: CryptoJSAesJson}).toString();
		$.post(baseURL + api + isoPais + "/lotes/confirmarPreOSL", {
			request: dataRequest,
			ceo_name: ceo_cook,
			plot: btoa(ceo_cook)
			})
			.done(function (response) {
			var	data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {
					format: CryptoJSAesJson
				}).toString(CryptoJS.enc.Utf8))
				$aux.dialog('destroy');
				var title, message, site = null,
					code = '';

				if (!data.ERROR) {
					title = 'Orden de Servicio emitida';
					message = '<div>';
					message += 'La orden de Servicio ha sido generada con éxito';
					if (data.costoLog) {
						message += ' y deberá ser pagada en los <b>próximos ' + data.daysPay + ' días</b> de lo contrario ';
						message += 'el sistema no le permitirá autorizar nuevos lotes';
					}
					message += '.';
					message += '</div>';
					if (data.moduloOS) {
						site = 'form#toOS';
						$("#data-confirm").attr('value', data.ordenes);
						ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						$('#toOS').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
					} else {

						message += '<h5>No tiene permitido gestionar ordenes de servicio.</h5>';
						site = '#viewAutorizar';
					}

				} else {

					if (data.ERROR == '-29') {
						code = 3;
						title = data.title;
						message = data.msg;
					} else if (data.ERROR == '-56') {
						title = 'Error de facturación';
						message = data.msg

					} else {
						title = 'Confirmar cálculo orden de servicio';
						message = data.ERROR;

					}

				}
				notificacion(title, message, site, code);

			});

	});


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


	function notificacion(titulo, mensaje, sitio, code) {

		var canvas = "<div>" + mensaje + "</div>";

		$(canvas).dialog({
			title: titulo,
			modal: true,
			maxWidth: 700,
			maxHeight: 300,
			bgiframe: true,
			resizable: false,
			draggable: false,
			dialogClass: 'hide-close',
			buttons: {
				Aceptar: function () {
					$(this).dialog("destroy");
					if (sitio) {
						$(sitio).submit();
					}
					if (code === 3) {
						$(location).attr('href', baseURL + isoPais + '/login');
					}
				}
			}
		});
	}

	$('#cancelar-OS').on('click', function () {
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#viewAutorizar').append($('#tempIdOrdenL'));
		$('#viewAutorizar').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
		$("#viewAutorizar").submit();

	});


}); // fin document ready
