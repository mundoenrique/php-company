var responseServ = $('#response-serv');
var codeServ = parseInt(responseServ.attr('code'));
var titleServ = responseServ.attr('title');
var msgServ = responseServ.attr('msg');
$(function () {
	if (codeServ != 0) {
		msgSystemrepor(codeServ, titleServ, msgServ);
	}
	var table = $('#novo-table').DataTable({
		select: false,
		dom: 'Bfrtip',
		"lengthChange": true,
		"searching": false,
		"pagingType": "full_numbers",
		"pageLength": 10, //Cantidad de registros por pagina
		language: {
			"url": baseCDN + '/media/js/combustible/Spanish.json'
		},
		buttons: [{
			text: '<span id="down-excel" aria-hidden="true" class="icon" data-icon="&#xe05a"></span>',
			className: 'down-report',
			titleAttr: "Descargar reporte excel"
		}],
	});

	table.on('draw', function () {
		var tableEmty = $('#novo-table > tbody > tr > td:first-child').hasClass("dataTables_empty");
		if (tableEmty) {
			$('#down-excel')
				.addClass('disabled')
				.attr('title', 'No hay datos para descargar');
		}
		$('#loading').hide();
		$('#display-table').fadeIn(1000);
	});

	$('td.os-info-show').on('click', function () {

		var tr = $(this).closest('tr');
		var row = table.row(tr);
		var trAct = $(this).closest('tbody').find('tr.shown');
		var rowAct = table.row(trAct)
		var inOs = tr.find('span.show-table').html();

		if (row.child.isShown()) {
			row.child.hide();
			tr.removeClass('shown');
		} else {
			if (rowAct.child.isShown()) {
				rowAct.child.hide();
				trAct.removeClass('shown');
			}
			row.child(inOs, 'os-info').show();
			tr.addClass('shown');
		}
	});

	$("tbody").on("click", ".batch-detail", function (e) {
		e.preventDefault();
		var idLote = $(this).attr('id');

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#detalle_lote').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
		$("#detalle_lote").append('<input type="hidden" name="data-lote" value="' + idLote + '" />');
		$("#detalle_lote").submit();

	});

	$('#display-table').on('click', '#down-excel', function () {
		var noData = $(this).hasClass('disabled');

		if (!noData) {
			var uri = '/consulta/servicio',
				method = 'GET',
				action = 'GetBatchesByInvoice';
			var preload = $('#loading').html();
			$('.down-report').hide();
			$('.dt-buttons')
				.append('<div id="load-report" class="loading">' + preload + '</div>')
				.fadeIn('1000');
			callService(uri, method, action, function (response) {
				if (response.code === 0) {
					uri = '/reportes/eliminar', method = 'POST', action = response.msg.file;

					window.location.href = response.msg.url + response.msg.file;

					callService(uri, method, action, function (response) {});

				} else {
					msgSystemrepor(response.code, response.title, response.msg);
				}
				$('#load-report').remove();
				$('.down-report').fadeIn('1000');

			});
		}

	});

});

function callService(uri, method, action, _response_) {
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);

	$.ajax({
		url: baseURL + isoPais + uri,
		type: method,
		data: {
			way: action,
			ceo_name: ceo_cook
		},
		datatype: 'JSON',
	}).done(function (response) {
		_response_(response);
	});
}

function msgSystemrepor(code, title, msg) {
	var msgSystem = $('#msg-system-report');
	msgSystem.dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function (event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#msg-info').append('<p>' + msg + '</p>');
		}
	});
	$('#close-info').on('click', function () {
		$(msgSystem).dialog('close');
		switch (code) {
			case 1:
				window.location.replace(baseURL + isoPais + '/dashboard/productos/detalle');
				break;
			case 2:
				window.location.replace(baseURL + isoPais + '/logout');
				break;
		}
	});
}
