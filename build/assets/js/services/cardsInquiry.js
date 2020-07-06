'use strict'
var table
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#tableCardInquiry').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");
		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	})

	$('#searchCardsBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#searchCardsForm');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('#loader-table').removeClass('hide');
			$('.hide-table').addClass('hide');
			$('#tableCardInquiry').dataTable().fnClearTable();
			$('#tableCardInquiry').dataTable().fnDestroy();

			verb = 'POST'; who = 'services', where = 'CardsInquiry'
			callNovoCore(verb, who, where, data, function (response) {
				insertFormInput(false);
				table = $('#tableCardInquiry').DataTable({
					"ordering": false,
					"pagingType": "full_numbers",
					data: response.data.cardsList,
					"columnDefs": [
						{
							"targets": 0,
							"className": "select-checkbox",
							"checkboxes": {
								"selectRow": true
							},
						},
						{
							"targets": 9,
							"className": "pb-0 px-1",
						}
					],
					"columns": [
						{
							data: function (data) {
								return ''
							}
						},
						{ data: 'cardNumber' },
						{ data: 'orderNumber' },
						{ data: 'bulkNumber' },
						{ data: 'issueStatus' },
						{ data: 'cardStatus' },
						{ data: 'name' },
						{ data: 'idNumber' },
						{
							data: function (data) {
								return '--'
							}
					 },
					 {
						 data(data) {
							 var options = '<div class="flex justify-center items-center">';
							if (data.options.actualizar_datos) {
								options += '<button class="btn mx-1 px-0" title="Actualizar datos" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-user-edit" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.consulta_saldo_tarjeta) {
								options += '<button class="btn mx-1 px-0" title="Consulta saldo tarjeta" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-envelope-open" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.bloqueo_tarjeta) {
								options += '<button class="btn mx-1 px-0" title="Bloqueo tarjeta" data-toggle="tooltip">';
								options += 	'<i class="icon icon-lock" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.desbloqueo) {
								options += '<button class="btn mx-1 px-0" title="Desbloqueo" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-chevron-up" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.entregar_a_tarjetahabiente) {
								options += '<button class="btn mx-1 px-0" title="Entregar a tarjetahabiente" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-arrow-right" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.enviar_a_empresa) {
								options += '<button class="btn mx-1 px-0" title="Enviar a empresa" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-user-card" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.recibir_en_empresa) {
								options += '<button class="btn mx-1 px-0" title="Recibir en empresa" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-building" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.recibir_en_banco) {
								options += '<button class="btn mx-1 px-0" title="Recibir en banco" data-toggle="tooltip">';
								options += 	'<i class="icon novoglyphs icon-user-building" aria-hidden="true"></i>';
								options += '</button>';
							}

							if (data.options.no_oper) {
								options += '<span class="btn mx-1 px-0" title="Cargo tarjeta" data-toggle="tooltip">';
								options += 	'-';
								options += '</span>';
							}

							 options += '</div>';

							 return options;
						 },
					 }
					],
					"table-layout": "fixed",
					"select": {
						"style": "multi",
						"info": false,
						selector: ':not(td:nth-child(-n+7))'
					},
					"language": dataTableLang
				})
				verifyOptions(response.data.operList)
			})
		}
	})
})

function verifyOptions(operList) {
	if (!operList) {
		var column = table.column('8');
		column.visible(!column.visible());
	}

	$('#loader-table').addClass('hide');
	$('.hide-table').removeClass('hide');
}
