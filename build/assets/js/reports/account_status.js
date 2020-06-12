'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('#resultsAccount').DataTable({
		"ordering": false,
		"responsive": true,
		"pagingType": "full_numbers",
		"columnDefs": [
			{
				"targets": 6,
				render: function (data, type, row) {
					return data.length > 20 ?
						data.substr(0, 20) + '…' :
						data;
				}
			},
		],
		"language": dataTableLang
	});
});
