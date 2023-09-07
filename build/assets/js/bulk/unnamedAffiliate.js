'use strict'
var inventoryBulkResults;

$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var unnaListBtn = $('#unna-list-btn');
	var bulkNumber = $('#bulkNumber');
	var unnaListTable = $('#inventoryBulkResults');

	inventoryBulkResults = $('#inventoryBulkResults').DataTable({
		drawCallback: function (d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
		"ordering": false,
		"pagingType": "full_numbers",
		"language": dataTableLang,
		"columns": [
			{ "data": "numberBulk"},
			{ "data": "elemCards"},
			{ "data": "emitionDate"},
			{ "data": "status"},
			{ "data": "affiliatedCards"},
			{ "data": "forAffiliateCards"},
			{ "data": "availableCards"},
			{ "data": "options"}
		],
		"columnDefs": [
			{
				"targets": 0,
				"className": "numberBulk",
				"visible": lang.SETT_TABLE_UNNAMED_CARDS == "ON"
			},
			{
				"targets": 1,
				"className": "elemCards",
				"visible": lang.SETT_TABLE_UNNAMED_CARDS == "ON"
			},
			{
				"targets": 2,
				"className": "emitionDate",
				"visible": lang.SETT_TABLE_UNNAMED_CARDS == "ON"
			},
			{
				"targets": 3,
				"className": "status",
				"visible": lang.SETT_TABLE_UNNAMED_CARDS == "ON"
			},
			{
				"targets": 4,
				"className": "affiliatedCards",
				"visible": lang.SETT_TABLE_AFFILIATED_COLUMNS == "ON"
			},
			{
				"targets": 5,
				"className": "forAffiliateCards",
				"visible": lang.SETT_TABLE_AFFILIATED_COLUMNS == "ON"
			},
			{
				"targets": 6,
				"className": "availableCards",
				"visible": lang.SETT_TABLE_AFFILIATED_COLUMNS == "ON"
			},
			{
				"targets": 7,
				"className": "options",
				"visible": lang.SETT_TABLE_UNNAMED_CARDS == "ON"
			}
		],
	});

	$('#initialDate, #finalDate').datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.SETT_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}

			if (inputDate == 'finalDate') {
				$('#initialDate').datepicker('option', 'maxDate', selectedDate);
			}

			if ($(this).val() != '') {
				$('input:radio').prop('checked', false);
			}
		}
	});

	bulkNumber.on('click keyup', function() {
		if ($(this).val() != '') {
			form = $('#unna-list-form')
			form.validate().resetForm();
			$('.help-block').text('');
			$('input:radio').prop('checked', false);
			$('#initialDate, #finalDate').datepicker('setDate', null);
		}
	});

	$(":radio").on('change', function () {
		form = $('#unna-list-form')
		form.validate().resetForm();
		$('.help-block').text('');
		bulkNumber.val('')
		$('#initialDate, #finalDate').datepicker('setDate', null);
	});

	unnaListBtn.on('click', function(e) {
		e.preventDefault();
		form = $('#unna-list-form');
		btnText = unnaListBtn.text().trim();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form)
			form = $('#get-data')
			$.each(data, function(index, value) {
				form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
			});
			insertFormInput(true, form)
			unnaListBtn.html(loader)
			form.submit()
		}
	})

	unnaListTable.on('click', 'button', function(e) {
		e.preventDefault();
		form = $(this).parent().find('form')
		insertFormInput(true, form);
		$('.cover-spin').show(0);
		form.submit();
	});
});
