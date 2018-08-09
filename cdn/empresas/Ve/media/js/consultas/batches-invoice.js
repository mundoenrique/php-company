$(function() {
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
	})
});
