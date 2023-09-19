export const getToken = function (module, _function_) {
	if (lang.SETT_ACTIVE_RECAPTCHA) {
		grecaptcha.ready(function () {
			grecaptcha.execute(lang.SETT_KEY_RECAPTCHA, { action: module }).then(
				function (token) {
					if (token) {
						token;
						_function_(token);
					}
				},
				function (token) {
					if (!token) {
						icon = lang.SETT_ICON_WARNING;
						modalBtn = {
							btn1: {
								text: lang.GEN_BTN_ACCEPT,
								link: lang.SETT_LINK_SIGNIN,
								action: 'redirect',
							},
						};

						appMessages(lang.GEN_SYSTEM_NAME, lang.GEN_SYSTEM_MESSAGE, icon, modalBtn);
					}
				}
			);
		});
	} else {
		_function_('');
	}
};
