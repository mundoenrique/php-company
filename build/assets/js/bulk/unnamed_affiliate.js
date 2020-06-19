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
		"language": dataTableLang
	});

	$('#initialDate, #finalDate').datepicker({
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'initialDate') {
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + 3);

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
			formInputTrim(form);
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
		form.submit();
	});
});
