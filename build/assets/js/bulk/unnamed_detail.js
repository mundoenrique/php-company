'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var downloadFile = $('#download-file')

	$('#unnamed-detail').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"language": dataTableLang
	});

	downloadFile.on('click', function(e) {
		e.preventDefault();
		form = $(this).parent().find('form');
		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.GEN_TIME_DOWNLOAD_FILE);
	})
});
