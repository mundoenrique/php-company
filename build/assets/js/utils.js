'use strict';
$(function () {
	$('form, input[type=text], input[type=password], input[type=email]').attr('autocomplete', 'off');
	let assetsTenant = cryptography.decrypt(assetsClient.response);

	$.each(assetsTenant, function (item, value) {
		window[item] = value;
	});

	delete assetsClient.response;
	loader = $('#loader').html();
});

const toggleDisableActions = function (disable) {
	$('button, select,textarea, input:not([type=hidden])').not('[ignore-el]').prop('disabled', disable);
};

const takeFormData = function (form) {
	let dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('name')] = $(element).val();
	});

	return dataForm;
};

const getLoader = function () {
	return $('#loader').html();
};

const uiModalMessage = function (title, message, icon, modalBtn) {
	const btn1 = modalBtn.btn1;
	const btn2 = modalBtn.btn2;
	const maxHeight = modalBtn.maxHeight || 350;

	$('#system-info').dialog({
		title: title || lang.GEN_SYSTEM_NAME,
		closeText: '',
		modal: 'true',
		position: { my: modalBtn.posMy || 'center', at: modalBtn.posAt || 'center' },
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		width: modalBtn.width || lang.SETT_MODAL_WIDTH,
		minWidth: modalBtn.minWidth || lang.SETT_MODAL_WIDTH,
		minHeight: modalBtn.minHeight || 100,
		maxHeight: maxHeight !== 'none' ? maxHeight : false,
		dialogClass: 'border-none',
		classes: {
			'ui-dialog-titlebar': 'border-none',
		},
		open: function (event, ui) {
			if (!modalBtn.close) {
				$('.ui-dialog-titlebar-close').hide();
			}

			$('#system-icon').removeAttr('class');

			if (icon != '') {
				$('#system-icon').addClass(lang.SETT_ICON + ' ' + icon);
			}

			$('#system-msg').html(message);

			if (!btn1) {
				$('#accept').addClass('hide');
			} else {
				uiModalButtons($('#accept'), btn1);
			}

			if (!btn2) {
				$('#cancel').addClass('hide');
			} else {
				uiModalButtons($('#cancel'), btn2);
			}
		},
	});
};

const uiModalButtons = function (elementButton, valuesButton) {
	elementButton.text(valuesButton.text);
	elementButton.show();
	elementButton.on('click', function () {
		switch (valuesButton.action) {
			case 'redirect':
				$(this).html(loader).prop('disabled', true);
				$(this).children('span').addClass('spinner-border-sm');

				if ($(this).attr('id') === 'cancel') {
					$(this).children('span').removeClass('secondary').addClass('primary');
				}

				$(location).attr('href', baseURL + valuesButton.link);
				break;

			case 'destroy':
				modalDestroy(true);
				break;
		}

		$(this).off('click');
	});
};

const uiMdalClose = function (close) {
	if ($('#system-info').parents('.ui-dialog').length && close) {
		$('#system-info').dialog('destroy');
		$('#accept')
			.prop('disabled', false)
			.html(lang.GEN_BTN_ACCEPT)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['accept'])
			.off('click');
		$('#cancel')
			.prop('disabled', false)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['cancel'])
			.html(lang.GEN_BTN_CANCEL)
			.off('click');
	}
};
