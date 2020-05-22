'use strict'
$(function () {
	$('#startingLine1').attr('maxlength', 25);
	$('#startingLine2').attr('maxlength', 25);
	$('#startingLine2').attr('value', );
	var unnamedReqBtn = $('#unnamed-request-btn');
	var maxCards = $('#maxCards');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$('#maxCards').keyup(function (){
		if(this.value == 0){
			this.value = (this.value + '').replace(0, '');
		}
		this.value = (this.value + '').replace(/[^0-9]]/g, '');
	 });

	 $('#startingLine1').keyup(function (){
		this.value = (this.value + '').replace(/[^A-Za-z0-9\s]+$/g, '');
	 });
	 $('#startingLine2').keyup(function (){
		this.value = (this.value + '').replace(/[^A-Za-z0-9\s]+$/g, '');
	 });

	unnamedReqBtn.on('click', function(e) {
		e.preventDefault();
		var unnamedBtn = $(this);
		form = $('#unnamed-request-form');
		btnText = unnamedBtn.text().trim();
		validateForms(form)

		if (form.valid()) {
			formInputTrim(form);
			data = getDataForm(form);
			data.password = cryptoPass(data.password);
			insertFormInput(true, form);
			unnamedBtn.html(loader);
			unnamedRequest(unnamedBtn);
		} else {
			if (maxCards.hasClass('has-error')) {
				var totalCards = parseInt(maxCards.attr('max-cards'));

				if(totalCards > 0) {
					var text = maxCards.siblings('.help-block').text();
					text+= ', '+lang.VALIDATE_MAXIMUM+' '+totalCards;
					maxCards.siblings('.help-block').text(text);
				}
			}
		}
	});
});

function unnamedRequest(unnamedBtn) {
	verb = 'POST'; who = 'Bulk'; where = 'UnnamedRequest'
	callNovoCore(verb, who, where, data, function(response) {
		insertFormInput(false)
		unnamedBtn.text(btnText);
	});
}
