'use strict'
var pendingBulk;
$(function () {
	if(code > 2 ) {
		$('#content-datatable').addClass('none');
		$('#no-bulk').removeClass('none');
	}

	var LoadBulk = getPropertyOfElement('loadbulk', '.loadbulk');
	var inputFile = LoadBulk ? $('#file-bulk').next('.js-label-file').html().trim() : '';

	$('.input-file').each(function () {
		var label = $(this).next('.js-label-file');
    var labelVal = label.html();

		$(this).on('change', function (element) {
			$(this)
				.focus()
				.blur();
      var fileName = '';
      if (element.target.value) fileName = element.target.value.split('\\').pop();
			fileName ? label.addClass('has-file').find('.js-file-name').html(fileName) : label.removeClass('has-file').html(labelVal);
			validInputFile();
    });
	});

	$('#type-bulk').on('change', function() {
		var getBranchOffices = [lang.BULK_GET_BRANCHOFFICE];
		if(getBranchOffices != []) {
			getBranchOffices.indexOf($(this).val()) != -1
			? $('#branch-office').prop('disabled', false).parent().removeClass('hide')
			: $('#branch-office').prop('disabled', true).parent().addClass('hide');
		}
	});

	$('#upload-file-btn').on('click', function(e) {
		e.preventDefault();
		var btnAction = $(this);
		btnText = btnAction.text().trim();
		form = $('#upload-file-form');
		validInputFile();
		validateForms(form);

		if(form.valid()) {
			$(this).html(loader);
			data = {
				file: $('#file-bulk')[0].files[0],
				branchOffice: $('#branch-office').val(),
				typeBulk: $('#type-bulk').val().trim(),
				formatBulk: $('#type-bulk option:selected').attr('format'),
				typeBulkText: $('#type-bulk option:selected').text().trim()
			}
			insertFormInput(true);
			who = 'Bulk';
			where = 'LoadBulk';

			callNovoCore(who, where, data, function(response) {
				btnAction.html(btnText);
				insertFormInput(false);
				$('#file-bulk').val('');
				$('#file-bulk').next('.js-label-file').html(inputFile);
				$('#branch-office').prop('selectedIndex', 0);
				$('#type-bulk').prop('selectedIndex', 0);
				respLoadBulk[response.code](response);
			});
		}
	});

	const respLoadBulk = {
		2: function(response) {
			appMessages(response.title, response.msg, response.icon, response.modalBtn);
		},
		3: function(response) {
			var msgModal = '';

			$.each(response.msg, function(item, content) {
				if(item == 'header') {
					$.each(content, function(index, value) {
						msgModal+= '<h5 class="regular mr-1">'+value+'</h5>';
					});
				}

				if(item == 'fields') {
					$.each(content, function(index, value) {
						msgModal+= '<h5>'+index+'</h5>';
						$.each(value, function(pos, val) {
							msgModal+= '<h6 class="light mr-1">'+val+'</h6>';
						})
					});
				}
			});

			appMessages(response.title, msgModal, response.icon, response.modalBtn);
		}
	}

  pendingBulk = $('#pending-bulk').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('#content-datatable').removeClass('hide');
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

	$('#pending-bulk').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');

		switch(action) {
			case lang.GEN_BTN_SEE:
				form = $(this).parent().find('form')
				insertFormInput(true, form);
				form.attr('action', baseURL + lang.SETT_LINK_BULK_DETAIL);
				form.append('<input type="hidden" name="bulkView" value="detail">');
			break;
			case lang.GEN_BTN_CONFIRM:
				form = $(this).parent().find('form')
				insertFormInput(true, form);
				form.attr('action', baseURL + lang.SETT_LINK_BULK_CONFIRM);
				form.append('<input type="hidden" name="bulkView" value="confirm">');
			break;
			case lang.GEN_BTN_DELETE:
				form = $(this).parent().find('form')
				$(this).closest('tr').addClass('select');
				$('#accept').addClass('delete-bulk-btn');
				modalBtn = {
					btn1: {
						text: lang.GEN_BTN_DELETE,
						action: 'none'
					},
					btn2: {
						text: lang.GEN_BTN_CANCEL,
						action: 'destroy'
					}
				}
				var bulkFile = form.find('input[name="bulkFile"]').val();
				var bulkDate = form.find('input[name="bulkDate"]').val();
				inputModal = '<form id="delete-bulk-form" name="delete-bulk-form" class="form-group" onsubmit="return false;">';
				inputModal += 	'<span>' + lang.BULK_DELETE + ' <strong>' + bulkFile + '</strong> de Fecha: <strong>' + bulkDate + '</strong></span>';

				if (lang.SETT_REMOTE_AUTH == 'OFF') {
					inputModal+=		'<div class="input-group">';
					inputModal+= 			'<input class="form-control pwd-input pwd-auth" name="password" type="password" ';
					inputModal+= 				'autocomplete="off" placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
					inputModal+=			'<div class="input-group-append">';
					inputModal+=				'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
					inputModal+=			'</div>';
					inputModal+=		'</div>';
					inputModal+= 		'<div class="help-block"></div>';
				}

				inputModal+= 	'</form>';
				appMessages(lang.BULK_DELETE_TITLE, inputModal, lang.SETT_ICON_INFO, modalBtn);
				$('#cancel').on('click', function(e) {
					e.preventDefault();
					$('#pending-bulk').find('tr').removeClass('select');
				});
			break;
		}

		if(action != lang.GEN_BTN_DELETE) {
			form.submit();
		}

		insertFormInput(false);
	})

	$('#system-info').on('click', '.delete-bulk-btn', function(e) {
		e.preventDefault();
		var formDeleteBulk = $('#delete-bulk-form');
		validateForms(formDeleteBulk);

		if(formDeleteBulk.valid()) {
			$(this)
				.off('click')
				.html(loader)
				.prop('disabled', true)
				.removeClass('delete-bulk-btn');
			data = {
				bulkId: form.find('input[name="bulkId"]').val(),
				bulkTicked: form.find('input[name="bulkTicked"]').val(),
				bulkStatus: form.find('input[name="bulkStatus"]').val(),
				bulkname: form.find('input[name="bulkFile"]').val(),
				bulkDate: form.find('input[name="bulkDate"]').val()
			}

			if (lang.SETT_REMOTE_AUTH == 'OFF') {
				data.pass = cryptoPass($('.pwd-auth').val());
			}

			insertFormInput(true, formDeleteBulk);
			who = 'Bulk';
			where = 'DeleteNoConfirmBulk';

			callNovoCore(who, where, data, function (response) {
				if(response.cod == 0) {
					pendingBulk.row('.select').remove().draw(false);
				}

				insertFormInput(false);
			});
		}
	});

})

function validInputFile() {
	form = $('#upload-file-form');
	validateForms(form);

	if ($('#file-bulk').valid()) {
		$('.js-label-file').removeClass('has-error');
	} else {
		$('.js-label-file').addClass('has-error');
	}
}
