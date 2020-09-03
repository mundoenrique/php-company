'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var datePicker = $('.date-picker');
	var resultStatusBulk = $('#resultStatusBulk');
	var statusBulkBtn = $('#status-bulk-btn');
	var downLoad = $('.download');

	datePicker.datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2]
			var inputDate = $(this).attr('id');
			var maxTime = new Date(dateSelected);

			if (inputDate == 'initialDate') {
				var maxMonth = parseInt(lang.CONF_MAX_CONSULT_MONTH);
				$('#finalDate').datepicker('option', 'minDate', selectedDate);
				maxTime.setDate(maxTime.getDate() - 1);
				maxTime.setMonth(maxTime.getMonth() + maxMonth);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	statusBulkBtn.on('click', function (e) {
		form = $('#status-bulk-form');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('.statusbulk-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide')
			resultStatusBulk.dataTable().fnClearTable();
			resultStatusBulk.dataTable().fnDestroy();
			verb = "POST"; who = 'Reports'; where = 'StatusBulk';
			callNovoCore(verb, who, where, data, function (response) {
				var table = resultStatusBulk.DataTable({
					"ordering": false,
					"responsive": true,
					"pagingType": "full_numbers",
					"language": dataTableLang
				});

				if (response.data.statusBulkList.length == 0) {
					$('.download-icons').addClass('hide')
				} else {
					$('.download-icons').removeClass('hide')
				}

				$.each(response.data.statusBulkList, function (index, value) {
					table.row.add([
						value.bulkType,
						value.bulkNumber,
						value.bulkStatus,
						value.uploadDate,
						value.valueDate,
						value.records,
						value.amount,
					]).draw()
				});
				form = $('#download-status');
				form.html('')
				$.each(data, function(index, value) {
					if(index != 'screenSize') {
						form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
					}
				});

				insertFormInput(false);
				statusBulkBtn.html(btnText);
				$('#pre-loade-result').addClass('hide')
				$('.statusbulk-result').removeClass('hide');
			})
		}
	});

	downLoad.on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $('#download-status');
		form.append('<input type="hidden" name="type" value="' + action + '"></input>');
		form.append('<input type="hidden" name="who" value="DownloadFiles"></input>');
		form.append('<input type="hidden" name="where" value="StatusBulkReport"></input>');
		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.GEN_TIME_DOWNLOAD_FILE);
	});
});

/* validator = $('#status-bulk-form').validate();
validator.destroy();
form.submit(); */
