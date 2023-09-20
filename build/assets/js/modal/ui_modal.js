import { appLoader } from '../utils.js';

export const uiModalMessage = function (modalArgs) {
	const width = modalArgs.width || lang.SETT_MODAL_WIDTH;
	const minWidth = modalArgs.minWidth || lang.SETT_MODAL_WIDTH;
	const maxWidth = modalArgs.maxWidth || lang.SETT_MODAL_WIDTH;
	const height = modalArgs.height || lang.SETT_MODAL_HEIGHT;
	const minHeight = modalArgs.minHeight || lang.SETT_MODAL_MINHEIGHT;
	const maxHeight = modalArgs.maxHeight || lang.SETT_MODAL_MAXHEIGHT;
	const icon = modalArgs.icon || false;
	const msg = modalArgs.msg || false;
	const btn1 = modalArgs.modalBtn.btn1;
	const btn2 = modalArgs.modalBtn.btn2;

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
		maxWidth: maxWidth,
		height: height,
		minHeight: minHeight,
		maxHeight: maxHeight,
		dialogClass: 'border-none',
		classes: {
			'ui-dialog-titlebar': 'border-none',
		},
		open: function (event, ui) {
			if (!modalArgs.close) {
				$('.ui-dialog-titlebar-close').hide();
			}

			$('#system-icon').removeAttr('class');

			if (icon && icon !== '') {
				$('#system-icon').addClass(lang.SETT_ICON + ' ' + icon);
			}

			if (msg && msg !== '') {
				$('#system-msg').html(msg);
			}

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
		$('#system-msg').html('');
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
