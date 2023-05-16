'use strict'
var authBulkDetail;
$(function() {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var downLoad = $('.download');

	authBulkDetail = $('#auth-bulk-detail').DataTable({
		drawCallback: function(d) {
			$('#loader-table').remove();
			$('.hide-table').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "language": dataTableLang
	});

	downLoad.on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		form = $('#download-detail-bulk');
		form.append('<input type="hidden" name="type" value="' + action + '"></input>');
		form.append('<input type="hidden" name="who" value="DownloadFiles"></input>');
		form.append('<input type="hidden" name="where" value="BulkDetailExport"></input>');
		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.SETT_TIME_DOWNLOAD_FILE);
	});
});
