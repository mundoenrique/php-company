'use strict'
var widthBrowser =$(window).innerWidth();

$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	if (widthBrowser <= 1040) {
		var table = $('#tableCardInquiry').DataTable({
			"ordering": false,
			"pagingType": "full_numbers",
			"columnDefs": [
				{
					"targets": 6,
					"width": "auto",
					"className": "inquiry", "targets": [0],
					render: function (data, type, row) {
						return data.length > 20 ?
							data.substr(0, 20) + '…' :
							data;
					}
				},
				{
					"targets": 6,
					"width": "70px"
				},
			],
			"table-layout": "fixed",
			"select": {
				"style": "multi",
				"info": false,
				selector: ':not(td:nth-child(-n+7))'
			},
			"language": dataTableLang
		});
	} else{
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
					"targets": 6,
					"width": "70px"
				},
			],
			"table-layout": "fixed",
			"select": {
				"style": "multi",
				"info": false,
				selector: ':not(td:nth-child(-n+7))'
			},
			"language": dataTableLang
		});
		$('#tableCardInquiry').on('click', '.toggle-all', function () {
			$(this).closest("tr").toggleClass("selected");
			if ($(this).closest("tr").hasClass("selected")) {
				table.rows().select();
			} else {
				table.rows().deselect();
			}
		});
	};
});
