'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('.date-picker').datepicker({
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
		}
	});

	$('#status-bulk-btn').on('click', function (e) {
		form = $('#status-bulk-form');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			$('.statusbulk-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide')
			$('#resultStatusBulk').dataTable().fnClearTable();
			$('#resultStatusBulk').dataTable().fnDestroy();
			who = 'Reports';
			where = 'StatusBulk';
			data = getDataForm(form);
			insertFormInput(true);

			callNovoCore(who, where, data, function (response) {
				var table = $('#resultStatusBulk').DataTable({
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
				form.html('');

				$.each(data, function(index, value) {
					if(index != 'screenSize') {
						form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
					}
				});

				insertFormInput(false);
				$('#status-bulk-btn').html(btnText);
				$('#pre-loade-result').addClass('hide')
				$('.statusbulk-result').removeClass('hide');
			})
		}
	});

	$('.download').on('click', 'button', function (e) {
    e.preventDefault();
    var event = $(e.currentTarget);
    var action = event.attr('title');

    if (lang.SETT_DOWNLOAD_SERVER === 'ON') {
      switch (action) {
        case lang.GEN_BTN_DOWN_XLS:
          StatusBulkDownloadFiles('generarArchivoXlsEstatusLotes','Xls')
          break;
        case lang.GEN_BTN_DOWN_PDF:
          StatusBulkDownloadFiles('generarPdfEstatusLotes','Pdf')
          break;
        case lang.GEN_BTN_DOWN_TXT:
          StatusBulkDownloadFiles('generarTxtEstatusLotes','Txt')
          break;
      }
    } else {
      downLoadReport(e)
    }
  });

  function downLoadReport(e) {
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
    }, lang.SETT_TIME_DOWNLOAD_FILE);
  }

  function StatusBulkDownloadFiles(operation, type) {
    var form = $('#status-bulk-form');
    var data = getDataForm(form)
    insertFormInput(true);
    who = 'DownloadFiles';
    where = `exportToStatusBulk`;
    data.operation = operation
    data.type = type
    callNovoCore(who, where, data, function (response) {
      if (response.code == 0) {
        $('#download-file').attr('href', response.data.file);
        document.getElementById('download-file').click();
        who = 'DownloadFiles';
        where = 'DeleteFile';
        data.fileName = response.data.name
        callNovoCore(who, where, data, function (response) {})
      }
      insertFormInput(false);
      $('.cover-spin').hide();
    })
  }
});

/* validator = $('#status-bulk-form').validate();
validator.destroy();
form.submit(); */
