'use strict'

var table = $('#tableContactsCompany');
var disabled = 'disabled';
var selected;
var dataEnterpriseList;
var contactList;

$(function () {

	if ((lang.CONF_ENTERPRICE_CONTACT == 'ON') && $('#idEnterpriseList').attr("countEnterpriseList")==1) {
		hideSection();
		form = $('#enterpriseSettListForm');
		getContacts(getDataForm(form));
	}else if ($('#idEnterpriseList').attr("countEnterpriseList")>1){
		$('#enterpriseData').hide();
		$('#sectionConctact').hide();
	};

	if ( lang.CONF_SETTINGS_PHONES_UPDATE == 'OFF' && $('#idEnterpriseList>option:selected').attr("countEnterpriseList")==1 ) {
		enablePhone();
	};

	$('ul.nav-config-box, .slide-slow').on('click', function (e) {
		if ($('#idEnterpriseList > option').length > 1) {
			$('#idEnterpriseList').prop('selectedIndex', 0);
			$('#enterpriseData').hide();
			$('#sectionConctact').hide();
		}
	})

	$('#updateEnterpriceBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#enterpriseDataForm');
		btnText = $(this).text().trim();

		switch (lang.CONF_LINK_UPDATE_ADDRESS_ENTERPRICE) {
			case 'changeTelephones':
				$("#address").addClass("ignore");
				$("#billingAddress").addClass("ignore");
				$("#phone2").addClass("ignore");
				$("#phone3").addClass("ignore");
				break;
			case 'changeDataEnterprice':
				$("#phone1").addClass("ignore");
				$("#phone2").addClass("ignore");
				$("#phone3").addClass("ignore");
				break;
		}

		validateForms(form);

		if (form.valid()) {
			who = 'Settings';
			where = lang.CONF_LINK_UPDATE_ADDRESS_ENTERPRICE;
			data = getDataForm(form);
			$(this).html(loader);
			insertFormInput(true);

			callNovoCore(who, where, data, function (response) {
				dataResponse = response.data;
				insertFormInput(false);
				$('#updateEnterpriceBtn').html(btnText);
			})
		}
	});

	$('#idEnterpriseList').on('change', function (e) {
		e.preventDefault();
		var optionSelect = $(this).find('option:selected');
		$('.hide-out').removeClass('hide');
		$('#enterpriseData').hide();
		$('#sectionConctact').hide();

		$.each( lang.SETTINGS_RENDER_CONTROLLER_VARIABLES, function( key ) {
			$('#'+ key).val(optionSelect.attr(key));
		});

		if ( lang.CONF_SETTINGS_PHONES_UPDATE == 'OFF' ) {
			enablePhone();
		};

		if ( lang.CONF_ENTERPRICE_CONTACT == 'ON' ) {
			form = $('#enterpriseSettListForm');
			validateForms(form);
				if (form.valid()) {
				hideSection();
				getContacts(getDataForm(form));
				}
		} else {
			$('#pre-loader').remove();
			hideSection();
			showSection();
		}
	});

	$('#newContactBtn').on('click', function(e) {
		showManageContactView("create");
		getTypeContact();
	});

	$('#backContactBtn').on('click', function(e) {
		$('#sectionConctact').fadeIn(700, 'linear');
		$('#btnSaveContact').removeAttr('data-action')
		$('#editAddContactSection').hide();
	});


	$("#btnSaveContact").on("click", function(e) {
		e.preventDefault();
		form = $('#addContactForm');
		validateForms(form);

		if (form.valid()) {
			var formEnterpriseList = $('#enterpriseSettListForm');
			dataEnterpriseList = getDataForm(formEnterpriseList);
			var btnAction = $('#btnSaveContact');
			btnText = btnAction.text().trim();
			btnAction.html(loader);
			var btn={};
			btn.btnAction = btnAction;
			btn.btnText = btnText;
			insertFormInput(true);
			data = getDataForm(form);
			data.idFiscal = dataEnterpriseList.idEnterpriseList;
			data.pass = cryptoPass(data.password1);
			delete data.password1;

			if ($(this).attr('data-action') == 'saveCreate') {
				data.action = 'addContact';
				getCallNovoCoreContact(data, btn);
			}else{
				data.action = 'updateContact';
				getCallNovoCoreContact(data, btn);
			}
		}
	});
});

function enablePhone(){
	for ( let i = 1; i < 4; i++ ) {
		if($('#phone'+ i).val()==''){
			$('#divPhone'+ i).addClass('hide');
		}else{
			$('#divPhone'+ i).removeClass('hide');
		}
	}
};

function showSection(){
	$('#enterpriseData').show();
	$('#sectionConctact').show();
	$('#existingContactButton').show();
};

function hideSection(){
	$('#enterpriseData').hide();
	$('#sectionConctact').hide();
	$('#editAddContactSection').hide();
	$('.hide-out').removeClass('hide');
};

function getContacts(value) {
	table.DataTable().destroy();
	data = value;
	who = 'Settings';
	where = 'getContacts';

	callNovoCore(who,where,data, function(response) {
		insertFormInput(false);
		showSection();
		if ( response.code === 0 ) {
			contactList =  response.data;
			contactsTable(contactList);

			$('#tableContactsCompany tbody').on('click', 'button', function (e) {
				$.each(contactList[$(this).val()], function (key, val) {
					$('#'+ key ).val(val);
				});
				var dataAction = $(this).attr('data-action');
				switch (dataAction) {
					case 'update':
						getTypeContact(contactList[$(this).val()].typeContactValue);
						showManageContactView("update")
						break;
					case 'delete':
						modalDeleteContact(contactList[$(this).val()]);
						break;
				}
			});
		}else if (response.code == 1){
			contactsTable(response.data);
		}
	});
};

function showManageContactView(action) {
	$('#sectionConctact').hide();
	$('#editAddContactSection').fadeIn(700, 'linear');
	$('.has-error').removeClass("has-error");
	$('.help-block').text('');
	switch (action) {
		case "create":
			$('#btnSaveContact').attr('data-action', 'saveCreate');
			$('#idExtPer').attr("readonly", false).removeClass('bg-tertiary border');
			$('#editAddContactText').html(lang.GEN_BTN_NEW +' '+ lang.GEN_CONTAC_PERSON.toLowerCase());
			$('#addContactForm')[0].reset();
			break;
		case "update":
			$('#btnSaveContact').attr('data-action', 'saveUpdate');
			$('#idExtPer').attr("readonly", true).addClass('bg-tertiary border');
			$('#editAddContactText').html(lang.GEN_EDIT +' '+ lang.GEN_CONTAC_PERSON.toLowerCase());
			$('#password1').val('');
			break;
	}
};

function getTypeContact(data) {
	$('#contactType').empty();
	$('#contactType').prepend('<option value="" selected ' + disabled + '>' + lang.GEN_BTN_SELECT + '</option>');
	$.each(lang.PRUE_ENTERPRICE_TYPE_CONTACT, function(key, val){
		selected = (data != '' && data == key) ? 'selected' : '';
		$('#contactType').append("<option value='"+ key +"' "+selected+">"+ val +"</option>");
	});
};

function getCallNovoCoreContact(data, btn){
	who = 'Settings';
	where = data.action;
	callNovoCore(who, where, data, function(response) {
		btn.btnAction.html(btn.btnText);

		if(response.code==0){
			appMessages(response.title, response.msg, response.icon, response.modalBtn);
			$('#accept').on('click', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				modalDestroy(true);
				var newData = {};
				newData.idEnterpriseList=data.idFiscal;
				hideSection();
				getContacts(newData);
			})
		}else{
			insertFormInput(false);
		}
	});
};

function modalDeleteContact(response) {
	modalDestroy(true);
	$('#accept').addClass('delete-contact-button');
	modalBtn = {
		btn1: {
			text: lang.GEN_BTN_ACCEPT,
			action: 'none'
		},
		btn2: {
			text: lang.GEN_BTN_CANCEL,
			action: 'none'
		}
	}
	inputModal = '<form id="delete-contact-form" name="delete-contact-form" class="form-group">';
	inputModal += '<span>';
	inputModal += '¿Esta seguro que quieres eliminar el contacto:<b> '+response.contactNames +' '+ response.contactLastNames+'</b>?';
	inputModal +=		'<br><br>';
	inputModal +=		'<div class="input-group">';
	inputModal += 		'<input class="form-control pwd-input pr-0 pwd" id="password1" name="password" type="password" ';
	inputModal += 				'autocomplete="off" placeholder="' + lang.GEN_PLACE_PASSWORD + '">';
	inputModal +=			'<div class="input-group-append">';
	inputModal +=				'<span class="input-group-text pwd-action" title="'+lang.GEN_SHOW_PASS+'"><i class="icon-view mr-0"></i></span>';
	inputModal +=			'</div>';
	inputModal +=		'</div>';
	inputModal += 	'<div class="help-block"></div>';
	inputModal += '</form>';

	appMessages(response.title, inputModal, lang.CONF_ICON_WARNING, modalBtn);

	$('.delete-contact-button').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		form = $('#delete-contact-form');
		var dataPassword = getDataForm(form);
		validateForms(form);
		if (form.valid()) {
			var btnAction = $('.delete-contact-button');
			var btn={};
			btn.btnText = btnAction.text().trim();;
			btn.btnAction = btnAction.html(loader);
			data = {};
			data.idFiscal = response.acrif;
			data.idExtPer = response.idExtPer;
			data.pass = cryptoPass(dataPassword.password1);
			data.action = 'deleteContact';
			$(this)
			.prop('disabled', true)
			.removeClass('delete-contact-button');
			insertFormInput(true);
			getCallNovoCoreContact(data, btn);
		}
	});
	$('#cancel').on('click', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$('#accept').removeClass('delete-contact-button');
		modalDestroy(true);
	});
}

function contactsTable(dataResponse) {
	$('.hide-out').addClass('hide');
	$('#contact-company-table').empty();
		var tableCont = '';
		tableCont +=  '<table id="tableContactsCompany" name="tableContactsCompany" class="mt-4 cell-border h6 display w-100 center">';
		tableCont +=  	'<thead class="bg-primary secondary regular">';
		tableCont +=     '<tr>';
		tableCont += 			'<th>'+ lang.GEN_TABLE_NAME_CLIENT +'</th>';
		tableCont +=      '<th>'+ lang.GEN_LAST_NAME +'</th>';
		tableCont +=      '<th>'+ lang.GEN_POSITION +'</th>';
		tableCont +=      '<th>'+ lang.GEN_TABLE_DNI +'</th>';
		tableCont +=      '<th>'+ lang.GEN_EMAIL +'</th>';
		tableCont +=      '<th>'+ lang.GEN_TABLE_TYPE +'</th>';
		tableCont +=      '<th>'+ lang.GEN_TABLE_OPTIONS +'</th>';
		tableCont +=     '</tr>';
		tableCont +=  '</thead>';
		tableCont +=  '<tbody>';
				$.each(dataResponse, function (index, value) {
				if (value) {
					tableCont += '<tr>';
					tableCont += 		'<td>' + value.contactNames + '</td>';
					tableCont += 		'<td>' + value.contactLastNames + '</td>';
					tableCont += 		'<td>' + value.contactPosition + '</td>';
					tableCont += 		'<td>' + value.idExtPer + '</td>';
					tableCont += 		'<td>' + value.contactEmail + '</td>';
					tableCont += 		'<td>' + value.typeContact + '</td>';
					tableCont += 		'<td>';
					tableCont += 		'<button value="'+ value.id +'" class="edit btn mx-1 px-0" title="'+lang.GEN_EDIT+'" data-action="update" data-toggle="tooltip">';
					tableCont += 		'<i class="icon icon-edit"></i>';
					tableCont += 		'</button>';
					tableCont += 		'<button value="'+ value.id +'" class="delete btn mx-1 px-0" title="'+lang.GEN_BTN_DELETE+'" data-action="delete" data-toggle="tooltip">';
					tableCont += 		'<i class="icon icon-remove"></i>';
					tableCont += 		'</button>';
					tableCont += 		'</td>';
					tableCont += '</tr>';
				}
				});
				tableCont += 	'</tbody>';
				tableCont += 	'</table>';

			$('#contact-company-table').append(tableCont);
			$('#tableContactsCompany').DataTable().destroy();
			$('#tableContactsCompany').DataTable({
				"autoWidth": false,
				"ordering": false,
				"searching": true,
				"lengthChange": false,
				"pagelength": 10,
				"pagingType": "full_numbers",
				"table-layout": "fixed",
				"language": dataTableLang,
				"columnDefs": [
					{
						"targets": 0,
						"className": "contactNames",
						"width": "200px"
					},
					{
						"targets": 1,
						"className": "contactLastNames",
						"width": "200px"
					},
					{
						"targets": 2,
						"className": "contactPosition",
						"width": "200px",
					},
					{
						"targets": 3,
						"className": "idExtPer",
						"width": "200px",
					},
					{
						"targets": 4,
						"className": "contactEmail",
						"width": "200px",
					},
					{
						"targets": 5,
						"className": "typeContact",
						"width": "200px",
					},
					{
						"targets": 6,
						"width": "auto"
					}
				]
			});
			return table;
};


