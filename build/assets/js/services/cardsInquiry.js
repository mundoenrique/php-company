'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	var table = $('#tableCardInquiry').DataTable({
		"ordering": false,
		"pagingType": "full_numbers",
		"columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {
					"selectRow": true
				},
			},
			{
				"className": "check-table", "targets": [0],
			},
		],
		"table-layout": "fixed",
		"select": {
			"style": "multi",
			"info": false,
			selector: ':not(td:nth-child(-n+7))'
		},
		"language": dataTableLang
	})

	$('#tableCardInquiry').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");
		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	})

	$('#searchCardsBtn').on('click', function(e) {
		e.preventDefault();
		form = $('#searchCardsForm');
		btnText = $(this).text().trim()
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			/* $(this).html(loader);
			insertFormInput(true); */
		}

	})
})
