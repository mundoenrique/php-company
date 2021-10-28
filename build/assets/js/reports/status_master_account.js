'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('.date-picker').datepicker({
		dateFormat: 'mm/yy',
		minDate: new Date(2019, 7, 1),
		maxDate: '-1M',
		showButtonPanel: true,
		onClose: function (dateText, inst) {
			console.log(dateText)
			console.log(inst)
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
			$(this)
				.focus()
				.blur();
		},
		beforeShow: function (input, inst) {
			inst.dpDiv.addClass("ui-datepicker-month-year");
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
		who = 'Reports';
		where = 'statusMasterAccount';

		callNovoCore(who, where, data, function (response) {
			$('#spinnerBlock').addClass('hide');

			if (response.code == 0) {
				$('#download-file').attr('href', response.data.file);
				document.getElementById('download-file').click();
				who = 'DownloadFiles';
				where = 'DeleteFile';
				data.fileName = response.data.name

				callNovoCore(who, where, data, function (response) { })
			}

			insertFormInput(false);
			$('#statusAccountForm').validate().resetForm();
		})
	}
});




