'use strict'

$(function () {
	var adminTable;
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
		var form;
		switch(action) {
			case lang.GEN_BTN_ENABLE_USER:
				form = $(this).parent().find('form[name=formManagement]');
				insertFormInput(true, form);
				var passData = form.toArray();
				var data;

				data = {
					name: passData[0][1].value,
					user:	passData[0][0].value,
					mail: passData[0][2].value,
					type: passData[0][3].value
				}

				$('#spinnerBlock').addClass('hide');

				validateForms(form);

				if (form.valid()) {
					insertFormInput(true, form);
					enableUser(data);
				}

				break;
			case lang.GEN_BTN_EDIT_PERMITS:
				form = $(this).parent().find('form[name=formManagement]');
				insertFormInput(true, form);
				form.attr('action', baseURL + lang.SETT_LINK_USERS_PERMISSIONS);
				form.append('<input type="hidden" name="editPermits" value="edit">');
				form.submit();
        break;
      case lang.GEN_BTN_EDIT_ACCOUNTS:
        form = $(this).parent().find('form[name=formManagement]');
        insertFormInput(true, form);
        form.attr('action', baseURL + lang.SETT_LINK_USERS_ACCOUNT);
        form.append('<input type="hidden" name="editPermits" value="edit">');
        form.submit();
        break;
		}
		insertFormInput(false);
	});

});


function enableUser(passData) {
	who = 'User';
	where = 'enableUser';
	data = passData;

	callNovoCore(who, where, data, function(response) {
		dataResponse = response.data;

		if (response.code == 4) {
			location.reload();
		}

		$('.cover-spin').removeAttr("style");
		insertFormInput(false);
	});
};

