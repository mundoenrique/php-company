'use strict'
function getRecaptchaToken(module, _function_) {
	if (lang.GEN_ACTIVE_RECAPTCHA) {
		grecaptcha.ready(function () {
			grecaptcha
				.execute(lang.GEN_KEY_RECAPTCHA, { action: module })
				.then(function (token) {
					if (token) {
						token
						_function_(token);
					}
				}, function (token) {
					if (!token) {
						icon = lang.CONF_ICON_WARNING;
						modalBtn = {
							btn1: {
								text: lang.GEN_BTN_ACCEPT,
								link: 'inicio',
								action: 'redirect'
							}
						};

						appMessages(lang.GEN_SYSTEM_NAME, lang.GEN_MESSAGE_SYSTEM, icon, modalBtn);
					}
				});
		});
	} else {
		_function_('');
	}
}
