'use strict'
var reportsResults;
$(function () {
	var datePicker = $('.date-picker');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	datePicker.datepicker({
		minDate: new Date(2021, 7, 1),
		maxDate: '-1M',
		changeMonth: true,
		changeYear: true,
		dateFormat: 'mm/yy',
		showButtonPanel: true,
		closeText: 'Aceptar',
		onSelect: function (selectedDate) {
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[0] + '/' + dateSelected[2];
			$(this)
			.focus()
			.blur();
		},
		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function (input, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('setDate', new Date(year, month, 1));
		}
	});

	$("#searchButton").on("click", function (e){
		e.preventDefault();
		var form = $('#statusAccountForm');
	  data = getDataForm(form);
		$('#spinnerBlock').addClass('hide');

		validateForms(form);
		if (form.valid()) {
			$('#spinnerBlock').removeClass('hide');
			getReport(data);
		}
	});

	function getReport(data) {
		insertFormInput(true);
		verb = 'POST'; who = 'Reports'; where = 'statusMasterAccount';
		var downloadFile = $('#download-file');
		callNovoCore(verb, who, where, data, function (response) {
			console.log(data);
			if (response.code == 0) {
				downloadFile.attr('href', response.data.file)
				document.getElementById('download-file').click()
				who = 'DownloadFiles'; where = 'DeleteFile';
				data.fileName = response.data.name
				callNovoCore(verb, who, where, data, function (response) { })
			}
			insertFormInput(false);
			$('#statusAccountForm').validate().resetForm();
			$('#pre-loader').remove();
		})
	}
});




