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
						 data: function (data) {
							 var options = '<div class="flex justify-center items-center">';
							 if (data.options.UPDATE_DATA) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_UPDATE_DATA+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-user-edit" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.INQUIRY_BALANCE) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_INQUIRY_BALANCE+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-envelope-open" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.LOCK_CARD) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_LOCK_CARD+'" data-toggle="tooltip">';
									options += 	'<i class="icon icon-lock" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.UNLOCK_CARD) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_UNLOCK_CARD+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-chevron-up" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.DELIVER_TO_CARDHOLDER) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_DELIVER_TO_CARDHOLDER+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-arrow-right" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.SEND_TO_ENTERPRISE) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_SEND_TO_ENTERPRISE+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-user-card" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.RECEIVE_IN_ENTERPRISE) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_RECEIVE_IN_ENTERPRISE+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-building" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.RECEIVE_IN_BANK) {
									options += '<button class="btn mx-1 px-0" title="'+lang.SERVICES_INQUIRY_RECEIVE_IN_BANK+'" data-toggle="tooltip">';
									options += 	'<i class="icon novoglyphs icon-user-building" aria-hidden="true"></i>';
									options += '</button>';
								}

								if (data.options.NO_OPER) {
									options += '<span class="btn mx-1 px-0" data-toggle="tooltip">';
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
				verifyOptions(response.data.operList, response.data.massiveOptions)
			})
		}
	})
})

function verifyOptions(operList, massiveOptions) {
	if (!operList.INQUIRY_BALANCE) {
		var column = table.column('8');
		column.visible(!column.visible());
	}

	$('#masiveOptions').children().remove();

	if (Object.keys(massiveOptions).length == 0) {
		$('#actionCArdsForm').addClass('hide')
	} else {
		$('#actionCArdsForm').removeClass('hide')
		$('#masiveOptions').append('<option selected disabled>Seleccionar</option>')
		$.each(massiveOptions, function(key, value) {
			$('#masiveOptions').append('<option value="'+key+'">'+value+'</option>')
		})
	}

	$('#loader-table').addClass('hide');
	$('.hide-table').removeClass('hide');
}
