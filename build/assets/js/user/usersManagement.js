'use strict'
var adminTable;
$(function () {

	adminTable = $('#consultAdminTable').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
		"ordering": false,
		"pagingType": "full_numbers",
		"columns": [
			null,
			null,
			null,
			{ "width": "160px" },
			{ "width": "130px" },
		],
		"columnDefs": [{
			"targets": 1,
			render: function ( data, type, row ) {
				return data.length > 20 ?
					data.substr( 0, 20 ) +'…' :
					data;
			}
		}],
		"autoWidth": false,
		"language": dataTableLang
	});

	$('#consultAdminTable').on('click', 'button',function(e){

		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		switch(action) {
			case lang.GEN_BTN_ENABLE_USER:
				form = $(this).parent().find('#formManagement')
				insertFormInput(true, form);
				form.attr('action', baseURL+'permisos-usuario');
				form.append('<input type="hidden" name="enableUser" value="enable">');
				break;
			case lang.GEN_BTN_EDIT_PERMITS:
				form = $(this).parent().find('form')
				insertFormInput(true, form);
				form.attr('action', baseURL+'permisos-usuario');
				form.append('<input type="hidden" name="editPermits" value="edit">');
				break;
		}

		form.submit();
		insertFormInput(false);
	});

});


