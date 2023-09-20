export const appLoader = $('#loader').html();

export const toggleDisableActions = function (disable) {
	$('button, select,textarea, input:not([type=hidden])').not('[ignore-el]').prop('disabled', disable);
};

export const takeFormData = function (form) {
	let dataForm = {};
	form.find('input, select, textarea').each(function (index, element) {
		dataForm[$(element).attr('name')] = $(element).val();
	});

	return dataForm;
};

export const spinerLoader = function (show) {
	show ? $('.cover-spin').show(0) : $('.cover-spin').hide();
};
