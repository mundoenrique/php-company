$(function() {
	$('#btn-pass-recover').on('click', function(e){
		e.preventDefault();
		var form = $('#form-pass-recovery');
		var recoverBtn = $(this);
		validateForms(form, {handleMsg: true});
		if(form.valid()) {
			var textBtn = recoverBtn.text();
			var recoverData = {
				userName: $('#user-name').val(),
				idEmpresa: $('#id-company').val(),
				email: $('#email').val()

			}
			$('form input, form button').attr('disabled', true);
			recoverBtn.html(loader);
			passRecover(recoverData, textBtn);
		}
	});
})

function passRecover(recoverData, textBtn) {
	verb = 'POST'; who = 'User'; where = 'RecoveryPass'; data = recoverData;
	callNovoCore(verb, who, where, data, function(response){
		dataResponse = response.data
		switch(response.code) {
			case 0:
			case 1:
				notiSystem(response.title, response.msg, response.icon, response.data)
				break;
		}
		response.code == 0 ? $('form')[0].reset() : '';
		$('form input, form button').attr('disabled', false);
		$('#btn-pass-recover').html(textBtn)
	});
}
