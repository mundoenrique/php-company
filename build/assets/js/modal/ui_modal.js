import { appLoader } from '../utils.js';

export const uiModalMessage = function (modalArgs) {
	const btn1 = modalArgs.modalBtn.btn1;
	const btn2 = modalArgs.modalBtn.btn2;
	const width = modalArgs.width || lang.SETT_MODAL_WIDTH;
	const minWidth = modalArgs.minWidth || lang.SETT_MODAL_WIDTH;
	const maxHeight = modalArgs.maxHeight || 350;
	const minHeight = modalArgs.minHeight || 100;

	$('#system-info').dialog({
		title: modalArgs.title || lang.GEN_SYSTEM_NAME,
		closeText: '',
		modal: 'true',
		position: { my: modalArgs.posMy || 'center', at: modalArgs.posAt || 'center' },
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		width: width,
		minWidth: minWidth,
		minHeight: minHeight,
		maxHeight: maxHeight !== 'none' ? maxHeight : false,
		dialogClass: 'border-none',
		classes: {
			'ui-dialog-titlebar': 'border-none',
		},
		open: function (event, ui) {
			if (!modalArgs.close) {
				$('.ui-dialog-titlebar-close').hide();
			}

			$('#system-icon').removeAttr('class');

			if (modalArgs.icon !== '') {
				$('#system-icon').addClass(lang.SETT_ICON + ' ' + modalArgs.icon);
			}

			$('#system-msg').html(modalArgs.msg || lang.GEN_SYSTEM_MESSAGE);

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
		$(this).html(appLoader);
		$(this).children('span').addClass('spinner-border-sm');

		if (action.hasOwnProperty(valuesButton.action)) {
			$(this).prop('disabled', true);
			$(this).siblings('.btn-modal').prop('disabled', true);

			if ($(this).attr('id') === 'cancel') {
				$(this).children('span').removeClass('secondary').addClass('primary');
			}

			action[valuesButton.action]();
		}

		// $(this).off('click');
	});

	const action = {
		redirect: function () {
			$(location).attr('href', baseURL + valuesButton.link);
		},
		destroy: function () {
			uiMdalClose(true);
		},
	};
};

export const uiMdalClose = function (close) {
	if ($('#system-info').parents('.ui-dialog').length && close) {
		$('#system-info').dialog('destroy');
		$('#system-msg').html(lang.GEN_SYSTEM_MESSAGE);
		$('#accept')
			.prop('disabled', false)
			.html(lang.GEN_BTN_ACCEPT)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['accept']);
		// .off('click');
		$('#cancel')
			.prop('disabled', false)
			.removeClass()
			.addClass(lang.SETT_MODAL_BTN_CLASS['cancel'])
			.html(lang.GEN_BTN_CANCEL);
		// .off('click');
	}
};
