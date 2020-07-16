'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$('#blockResults').addClass('hidden');
	insertFormInput(false);

	$('#card-holder-btn').on('click', function(){
		var	passData = {
			idNumberP: $('#idNumberP').val(),
			cardNumberP: $('#cardNumberP').val()
		}
		var form = $('#formTwirls');
		validateForms(form)
		if (form.valid()) {
			$('#spinnerBlockBudget').removeClass("hide");
			insertFormInput(false);
			getSwitchTwirls(passData);
		}
	});
});

function getSwitchTwirls(passData) {
	verb = "POST"; who = 'Services'; where = 'commercialTwirls'; data = passData;
	callNovoCore(verb, who, where, data, function(response) {
			dataResponse = response.data;
			code = response.code
			var info = dataResponse;

			if(code == 0){
				setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del serivico y se vea el spinner.
				$('#spinnerBlockBudget').addClass("hide");
				$('#blockResults').removeClass('hidden');
			}, 3000);
		}
})
}
