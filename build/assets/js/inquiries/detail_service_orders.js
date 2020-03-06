'use strict'
var resultServiceOrders;
$(function() {

	resultServiceOrders = $('#authLotDetail').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
    "ordering": false,
    "pagingType": "full_numbers",
    "language": dataTableLang
	});

	$('#downXLS').on('click', function(){
		exportFileType('xls');
		});

	$('#downPDF').on('click', function(){
		exportFileType('pdf');
	});

	function exportFileType($fileType){

		insertFormInput(true, $('#exportTo'));
		$('#exportTo').attr('action', baseURL + "descargar-archivo-os");
		$('#exportTo').append('<input type="hidden" name="views" value="detailServiceOrders">');
		$('#exportTo').append('<input type="hidden" name="who" value="Inquiries">');
		$('#exportTo').append('<input type="hidden" name="where" value="DetailExportFiles">');
		$('#data_lote').val($("#data_lote").val());
		$('#file_type').val($fileType);
		$('#exportTo').submit();

	}

})
