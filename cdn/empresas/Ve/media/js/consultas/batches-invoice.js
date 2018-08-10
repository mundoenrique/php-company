var responseServ = $('#response-serv');
var codeServ = parseInt(responseServ.attr('code'));
var titleServ = responseServ.attr('title');
var msgServ = responseServ.attr('msg');
$(function() {
	if(codeServ != 0) {
		msgSystemrepor(codeServ, titleServ, msgServ);
	}
	var table = $('#novo-table').DataTable({
		select: false,
		dom: 'Bfrtip',
		"lengthChange": true,
		"searching": false,
		"pagingType": "full_numbers",
		"pageLength": 10, //Cantidad de registros por pagina
		language: { "url":  baseCDN + '/media/js/combustible/Spanish.json'},
		/*
		buttons: [
			{
				text: '<span id="down-excel" aria-hidden="true" class="icon" data-icon="&#xe05a"></span>',
				className: 'down-report',
				titleAttr: "Descargar reporte excel"
			}
		],
		*/
	});

	table.on('draw', function(){
		$('#loading').hide();
		$('#display-table').fadeIn(1000);
	});

	$('td.os-info-show').on('click', function() {
		console.log(table.row)
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
			row.child( inOs, 'os-info' ).show();
			tr.addClass('shown');
		}
	});

	$("tbody").on("click",".batch-detail", function(e) {
		e.preventDefault();
		var idLote = $(this).attr('id');

		$("#detalle_lote").append('<input type="hidden" name="data-lote" value="'+idLote+'" />');
		$("#detalle_lote").submit();

	});
});

function msgSystemrepor(code, title, msg) {
	var msgSystem = $('#msg-system-report');
	msgSystem.dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#msg-info').append('<p>' + msg + '</p>');
		}
	});
	$('#close-info').on('click', function(){
		$(msgSystem).dialog('close');
		switch(code) {
			case 1:
				window.location.replace(baseURL + isoPais + '/dashboard/productos/detalle');
				break;
			case 2:
				window.location.replace(baseURL + isoPais + '/logout');
				break;
		}
	});
}
