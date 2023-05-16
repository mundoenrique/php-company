'use strict'
var signBulk;
var authorizeBulk;
var bulkData;
var tableSelected;
$(function () {
	remoteFunction = 'SignDeleteBulk';
	var sign = getPropertyOfElement('sign', '#sign-bulk');
	var auth = getPropertyOfElement('auth', '#authorize-bulk');
	var modalReq = {};

	signBulk = $('#sign-bulk').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      {
        "targets": 0,
        "className": "select-checkbox",
				"checkboxes": {"selectRow": true},
				'visible': sign != false,
      },
      {
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
		],
		"autoWidth": false,
    "select": {
			"style": lang.SETT_BULK_SELECT_ALL_SIGN == 'ON' ? 'multi' : 'single',
			"info": false,
			selector: ':not(td:nth-child(-n+6))'
    },
    "language": dataTableLang
	});

  $('#sign-bulk').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      signBulk.rows().select();
    } else {
      signBulk.rows().deselect();
    }
  });

  authorizeBulk = $('#authorize-bulk').DataTable({
		drawCallback: function(d) {
			$('#pre-loader').remove();
			$('.hide-out').removeClass('hide');
		},
		"autoWidth": false,
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {"selectRow": true},
				'visible': auth != false,
      	render: function (data, type, row) {
					var content = '';
					if(data != '') {
						content = '<button class="btn px-0" title="'+lang.GEN_BTN_DISASS_AUTH+'" data-toggle="tooltip">';
						content+= 	'<i class="icon icon-user" aria-hidden="true"></i>';
						content+= '</button>';
					}
					return content;
        }
      },
			{
        "targets": 2,
        "visible": false
      },
      {
        "targets": 5,
        "visible": false
      }
		],
		"select": {
      "style": lang.SETT_BULK_SELECT_ALL_AUTH == 'ON' ? 'multi' : 'single',
      "info": false,
      selector: ':not(td:nth-child(-n+6))'
    },
    "language": dataTableLang
	});

	$('#authorize-bulk').on('click', '.toggle-all', function () {
    $(this).closest('tr').toggleClass('selected');
    if ($(this).closest('tr').hasClass('selected')) {
      authorizeBulk.rows(':not(.no-select-checkbox)').select();
    } else {
      authorizeBulk.rows().deselect();
    }
	});

	$('#sign-bulk-btn, #del-sign-bulk-btn, #auth-bulk-btn, #del-auth-bulk-btn').on('click', function(e) {
		e.preventDefault()
		var action = $(this).text().trim();
		var thisId = $(this).attr('id');
		var passwordSignAuht = $(this).closest('form').find('input.pwd');
		form = $(this).closest('form');
		btnRemote = $(this);

		if (lang.SETT_REMOTE_AUTH == 'ON' && thisId != 'auth-bulk-btn') {
			SignDeleteBulk(form, action, thisId, passwordSignAuht, modalReq);
		} else if (lang.SETT_REMOTE_AUTH == 'ON') {
			if (signAuthBulkValidate(form, action, modalReq)) {
				remoteAuthArgs.form = form;
				remoteAuthArgs.action = action;
				remoteAuthArgs.passwordSignAuht = passwordSignAuht;
				remoteAuthArgs.modalReq = modalReq;
				btnText = action;
				getauhtKey();
			}
		} else {
			SignDeleteBulk(form, action, thisId, passwordSignAuht, modalReq);
		}



	});

	$('#sign-bulk, #authorize-bulk').on('click', 'button', function(e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		var submitForm = false;
		$(this).closest('tr').addClass('select');

		switch(action) {
			case lang.GEN_BTN_SEE:
				form = $(this).siblings('form');
				insertFormInput(true, form)
				form.submit();
				break;
			case lang.GEN_BTN_DELETE:
			case lang.GEN_BTN_DISASS_SIGN:
				var titleModal = action === lang.GEN_BTN_DELETE ? lang.BULK_DELETE_TITLE : lang.BULK_DISASS_TITLE;
				var textModal = action === lang.GEN_BTN_DELETE ? lang.BULK_DELETE : lang.BULK_DISASS;
				var oldId = $('#accept').attr('id');
				var currentIdBtn = 'delete-bulk-btn';
				var cancelDelete = $('#cancel');
				$('#accept').show().attr('id', currentIdBtn);
				var bulkNum = $(this).closest('tr').find('.bulk-num').text();
				modalReq['table'] = $(this).closest('table');
				modalBtn = {
					btn1: {
						text: lang.GEN_BTN_DELETE,
						action: 'close'
					},
					btn2: {
						text: lang.GEN_BTN_CANCEL,
						action: 'close'
					}
				}
				inputModal = 	'<form id="delete-bulk-form" name="delete-bulk-form" class="form-group" onsubmit="return false;">';
				inputModal+= 		'<span class="regular"> '+textModal+' '+bulkNum+'</span>';

				if (lang.SETT_REMOTE_AUTH == 'OFF') {
					inputModal+= 		'<div class="input-group">';
					inputModal+= 			'<input class="form-control pwd-input pwd-auth" name="password" type="password" ';
					inputModal+= 				'autocomplete="off" placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
					inputModal+= 			'<div class="input-group-append">';
					inputModal+= 				'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
					inputModal+= 			'</div>';
					inputModal+= 		'</div>';
					inputModal+= 		'<div class="help-block"></div>';
				}

				inputModal+=	'</form>';
				appMessages(titleModal, inputModal, lang.SETT_ICON_INFO, modalBtn);
				$('#'+currentIdBtn).on('click', function(e) {
					e.preventDefault();
					form = $('#delete-bulk-form');
					var passwordDelete = $('.pwd-auth');
					modalReq['oldId'] = oldId;
					SignDeleteBulk(form, action, currentIdBtn, passwordDelete, modalReq);
				});
				cancelDelete.on('click', function(e) {
					$('#'+currentIdBtn)
					.off('click')
					.attr('id', oldId);
				});
				break;
		}

		if(submitForm) {
			form.submit();
		}

	});
});

function SignDeleteBulk(currentForm, action, btnId, passwordInput, modalReq) {

	if (signAuthBulkValidate(currentForm, action, modalReq)) {
		var bulkInfo = [];
		var btnAction = $('#'+btnId);
		btnText = btnAction.text().trim();
		btnAction.html(loader);

		for(var i = 0; i < bulkData.length; i++) {
			var info = {};
			info['bulkNumber'] = bulkData[i][1];
			info['bulkId'] = bulkData[i][2];
			info['bulkIdType'] = bulkData[i][5];
			bulkInfo.push(JSON.stringify(info));
		}

		data = {
			bulk: bulkInfo,
		}

		if (lang.SETT_REMOTE_AUTH == 'OFF') {
			data.pass = cryptoPass(passwordInput.val().trim());
		}

		if(currentForm.attr('id') == 'auth-bulk-form') {
			data['typeOrder'] = $('#type-order').val()
		}

		if (modalReq.oldId) {
			btnAction
			.off('click')
			.prop('disabled', true)
			.attr('id', modalReq.oldId);
		}

		insertFormInput(true);

		switch(action) {
			case lang.GEN_BTN_SIGN:
				where = 'SignBulkList';
				break;
			case lang.GEN_BTN_AUTHORIZE:
				where = 'AuthorizeBulk';
				break;
			case lang.GEN_BTN_DELETE:
				where = 'DeleteConfirmBulk';
				break;
			case lang.GEN_BTN_DISASS_SIGN:
				where = 'DisassConfirmBulk';
				break;
		}

		who = 'Bulk';

		callNovoCore(who, where, data, function(response) {
			if(response.code == 0 && where == 'AuthorizeBulk') {
				$(location).attr('href', response.data);
			} else {
				btnAction.html(btnText);
				insertFormInput(false);
				passwordInput.val('');
				tableSelected.rows().deselect();
				appMessages(response.title, response.msg, response.icon, response.modalBtn);
				$('.cover-spin').hide();
			}
		});
	}
}

function signAuthBulkValidate(currentForm, action, modalReq) {
	validateForms(currentForm);
	var classSelect = '.selected:not(.no-select-checkbox)'
	switch (currentForm.attr('id')) {
		case 'sign-bulk-form':
			tableSelected = signBulk;
			break;
		case 'auth-bulk-form':
			tableSelected = authorizeBulk;
			break;
		default:
			classSelect = '.select';

			if (modalReq.table.attr('id') == 'sign-bulk') {
				tableSelected = signBulk;
			}

			if (modalReq.table.attr('id') == 'authorize-bulk') {
				tableSelected = authorizeBulk;
			}
	}

	bulkData = tableSelected.rows(classSelect).data();

	if (bulkData.length == 0) {
		currentForm.validate().resetForm();
		modalBtn = {
			btn1: {
				text: lang.GEN_BTN_ACCEPT,
				action: 'destroy'
			}
		}

		appMessages(action, lang.BULK_SELECT, lang.SETT_ICON_WARNING, modalBtn);
	}

	return bulkData.length > 0 && currentForm.valid();

}
