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
		$('#billingAddress').val(optionSelect.attr('billingAddress'));

		if (lang.CONF_SETTINGS_TELEPHONES == 'ON') {
			$('#phone1').val(optionSelect.attr('phone1'));
			$('#phone2').val(optionSelect.attr('phone2'));
			$('#phone3').val(optionSelect.attr('phone3'));
		}

		$('.hide-out').addClass('hide');
		$('#enterpriseData').removeClass('hide');
	})

	$('#userDataBtn').on('click', function (e) {
		e.preventDefault();
		form = $('#userDataForm');
		btnText = $(this).text().trim();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			data.email = $('#currentEmail').val().toLowerCase();
			insertFormInput(true);
			$(this).html(loader);
			verb = "POST"; who = 'Settings'; where = 'changeEmail';
			callNovoCore(verb, who, where, data, function (response) {
				dataResponse = response.data
				insertFormInput(false);
				$('#userDataBtn').html(btnText)
			})
		}
	})

	if (lang.CONF_APPS_DOWNLOAD.length > 0) {
		$('a.' + lang.CONF_APPS_DOWNLOAD[0][0]).on('click', function () {
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
