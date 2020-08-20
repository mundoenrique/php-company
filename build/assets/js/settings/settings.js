'use strict'
$(function () {
	var ulOptions = $('.nav-item-config');

	$.each(ulOptions, function (pos, liOption) {
		$('#' + liOption.id).on('click', function (e) {
			var liOptionId = e.currentTarget.id;
			$(ulOptions).removeClass('active');
			$('.option-service').hide();
			$(this).addClass('active');
			$('#' + liOptionId + 'View').fadeIn(700, 'linear');
		})
	})

	$('.slide-slow').on('click', function () {
		$('.section').slideToggle('slow');
	})

	$('ul.nav-config-box, .slide-slow').on('click', function (e) {
		var event = $(e.currentTarget)

		if (!event.hasClass('slide-slow')) {
			$('.section').hide();
		}

		$('input, select').removeClass('has-error');
		$('.help-block').text('');

		if ($('#enterpriseList > option').length > 1) {
			$('#enterpriseList').prop('selectedIndex', 0);
			$('#enterpriseDataForm')[0].reset();
			$('#passwordChangeForm')[0].reset();
			$('#enterpriseData').addClass('hide');
		}
	})

	$('.nav-item-config:first-child').addClass('active');
	var firstActive = $('.nav-config-box > li:first-child').attr('id');
	$('#' + firstActive + 'View').show();

	$('#enterpriseList').on('change', function () {
		var optionSelect = $(this).find('option:selected');
		$('#enterpriseData').addClass('hide');
		$('.hide-out').removeClass('hide');
		$('#idFiscal').val(optionSelect.attr('idFiscal'));
		$('#name').val(optionSelect.attr('name'));
		$('#businessName').val(optionSelect.attr('businessName'));
		$('#contact').val(optionSelect.attr('contact'));
		$('#address').val(optionSelect.attr('address'));

		if (lang.CONF_SETTINGS_TELEPHONES == 'ON') {
			$('#phone1').val(optionSelect.attr('phone1'));
			$('#phone2').val(optionSelect.attr('phone2'));
			$('#phone3').val(optionSelect.attr('phone3'));
		}

		$('.hide-out').addClass('hide');
		$('#enterpriseData').removeClass('hide');
	})

	if (lang.CONF_APPS_DOWNLOAD.length > 0) {
		$('a.download-ini' + lang.CONF_APPS_DOWNLOAD[0][0]).on('click', function () {
			if ($(this).attr('title') == '') {
				verb = 'POST'; who = 'Settings'; where = 'GetFileIni';
				data = {};
				callNovoCore(verb, who, where, data, function (response) {
					if (response.code == 0) {
						downLoadfiles(response.data);
					}
					$('.cover-spin').hide();
				})
			}
		})
	}
})


$(function () {

	var enterpriseWidgetForm = $('#enterprise-widget-forms');
	var changePassForm = $('#formChangePass');
	var changeEmailForm = $('#formChangeEmail');
	var addContactForm = $("#formAddContact");
	var changeTelephoneForm = $('#formChangeTelephones');
	var WidgetSelcet = $('#enterprise-select');
	var buttonEmail = $('#btnChangeEmail');
	var currentEmail = $('#currentEmail');
	var buttonTelephone = $('#btnChangeTelephones');
	var buttonClean = $("#btnLimpiar");
	var buttonContact = $('#btnAddContact');

	buttonEmail.on('click', function (e) {
		e.preventDefault();
		form = changeEmailForm;
		btnText = $(this).text().trim();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			data.email = currentEmail.val().toLowerCase();
			insertFormInput(true, form);
			$(this).html(loader);
			changeEmail(data, btnText);
		}
	});

	// Email Change End

	// Telephones Change
	$('#tlf1').keyup(function () {
		this.value = (this.value + '').replace(/[^0-9]+$/g, '');
	});
	$('#tlf2').keyup(function () {
		this.value = (this.value + '').replace(/[^0-9]+$/g, '');
	});
	$('#tlf3').keyup(function () {
		this.value = (this.value + '').replace(/[^0-9]+$/g, '');
	});
	buttonTelephone.on('click', function (e) {
		e.preventDefault();
		changeBtn = $(this);
		form = changeTelephoneForm;
		btnText = changeBtn.text().trim();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			tlf1 = $('#tlf1').val();
			tlf2 = $('#tlf2').val();
			tlf3 = $('#tlf3').val();
			acrif = $('#acrif').val();
			var passData = {
				tlf1: tlf1,
				tlf2: tlf2,
				tlf3: tlf3,
				acrif: acrif
			};
			insertFormInput(true, form);
			changeBtn.html(loader);
			changeTelephones(passData, btnText);

		}
	});

	// Telephones Change End

	// Add Contact
	buttonClean.click(function (e) {
		addContactForm[0].reset();
	});

	buttonContact.on('click', function (e) {
		e.preventDefault();
		changeBtn = $(this);
		form = addContactForm;
		btnText = changeBtn.text().trim();
		validateForms(form)

		if (form.valid()) {
			data = getDataForm(form)
			nombres = $('#contName').val();
			apellido = $('#surname').val();
			cargo = $('#contOcupation').val();
			idExtPer = $('#contNIT').val();
			email = $('#contEmail').val();
			tipoContacto = $('#contType').val();
			username = $('#contUser').val();
			password = $('#contPass').val();
			acrif = $('#contAcrif').val();
			var passData = {
				nombres: nombres,
				apellido: apellido,
				cargo: cargo,
				idExtPer: idExtPer,
				email: email,
				tipoContacto: tipoContacto,
				acrif: acrif,
				usuario: {
					userName: userName,
					password: password
				}
			};
			insertFormInput(true, form);
			changeBtn.html(loader);
			addContact(passData, btnText);
		}
	});

	// Add Contact End

	// Selector empresas

	enterpriseWidgetForm.on('change', function () {
		$("#completeForm").addClass("hide");
		var numpos = WidgetSelcet.find('option:selected').attr('numpos');
		var nameBusine = WidgetSelcet.find('option:selected').attr('name');
		var acrif = WidgetSelcet.find('option:selected').attr('acrif');
		var razonSocial = WidgetSelcet.find('option:selected').attr('razonSocial');
		var contacto = WidgetSelcet.find('option:selected').attr('contacto');
		var ubicacion = WidgetSelcet.find('option:selected').attr('ubicacion');
		var fact = WidgetSelcet.find('option:selected').attr('fact');
		var tel1 = WidgetSelcet.find('option:selected').attr('tel1');
		var tel2 = WidgetSelcet.find('option:selected').attr('tel2');
		var tel3 = WidgetSelcet.find('option:selected').attr('tel3');

		var passData = {
			numpos: numpos,
			acrif: acrif,
			nameBusine: nameBusine,
			razonSocial: razonSocial,
			contacto: contacto,
			ubicacion: ubicacion,
			fact: fact,
			tel1: tel1,
			tel2: tel2,
			tel3: tel3,
		};
		$('.hide-out').removeClass("hide");
		selectionBussine(passData);
	});
});

function changeEmail(passData, textBtn) {
	verb = "POST";
	who = 'Settings';
	where = 'changeEmail';
	data = passData;
	callNovoCore(verb, who, where, data, function (response) {
		dataResponse = response.data
		insertFormInput(false, form);
		$('#btnChangeEmail').html(textBtn)
	})
}

function changePassword1(passData, textBtn) {
	verb = "POST";
	who = 'Settings';
	where = 'ChangePassword';
	data = passData;
	callNovoCore(verb, who, where, data, function (response) {

		insertFormInput(false, form);
		changeBtn.html(textBtn)
	})
}

function addContact(passData, textBtn) {
	verb = "POST";
	who = 'Settings';
	where = 'addContact';
	data = passData;
	callNovoCore(verb, who, where, data, function (response) {
		dataResponse = response.data
		insertFormInput(false, form);
		changeBtn.html(textBtn)
	})
}

function changeTelephones(passData, textBtn) {
	verb = "POST";
	who = 'Settings';
	where = 'changeTelephones';
	data = passData;
	callNovoCore(verb, who, where, data, function (response) {
		dataResponse = response.data
		insertFormInput(false, form);
		changeBtn.html(textBtn)
	})

}
