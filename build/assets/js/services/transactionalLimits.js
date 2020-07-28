'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false);
	$('#blockResults').addClass('hidden');

	$('.slide-slow').click(function() {
		$(this).next(".section").slideToggle("slow");
		$(".help-block").text("");
	});

	$('.money').mask('000.000.000.000.000,00', {reverse: true});

	$('#card-holder-btn').on('click', function(){
		$('#blockResults').addClass('hidden');
		$('#spinnerBlock').removeClass("hide");
		var form = $('#limitsForm');
		var passData = getDataForm(form);
		validateForms(form);
		if (form.valid()) {
			insertFormInput(true);
			getForm(passData);
		}
	});

	$('#sign-bulk-btn').on('click', function(){
		var form = $('#limitsUpdateForm');
		var passData = getDataForm(form);
		var changeBtn = $('#sign-bulk-btn');
		var btnText = changeBtn.text().trim();
		validateForms(form);
		if (form.valid()) {
			changeBtn.html(loader);
			insertFormInput(true, form);
			updateLimits(passData, btnText);
		}
	});
});

function getForm(passData){
	verb = "POST"; who = 'Services'; where = 'transactionalLimits'; data = passData;	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
			setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del servicio y se vea el spinner.
				insertFormInput(false);
				$('#spinnerBlock').addClass("hide");
				$('#blockResults').removeClass('hidden');
			}, 3000);
		}
	})
};

function updateLimits(passData, btnText){
	verb = "POST"; who = 'Services'; where = 'updateTransactionalLimits'; data = passData;	callNovoCore(verb, who, where, data, function(response) {
		dataResponse = response.data;
		code = response.code
		var info = dataResponse;
		if(code == 0){
			setTimeout(function(){ // Esto es solo para simular el tiempo de ejecucion del servicio y se vea el spinner.
				insertFormInput(false);
				$('input[type=password]').val('');
				$('#sign-bulk-btn').html(btnText);
		}, 3000);
		}
	})
};
