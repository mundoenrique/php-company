$(function() {
	$('#continuar').on('click', function(e){
		e.preventDefault()
		var form = $('#pass-recovery');
		validateForms(form);
		if(form.valid()) passRecover()
	});
})

function passRecover() {

}
