$(function() {
	var code = $('#content-products').attr('code'), title, message, type;
	switch(code){
		case '2':
			type = 'serv';
			break;
		case '3':
			type = 'close';
			break;
	}
	if(code !== '0') {
		title = $('#content-products').attr('title');
		message = $('#content-products').attr('msg');
		notiSystem(title, message, type);
	}

	$('#data-payment').show();
	$('#loading').hide();

	$('#payment').on('click', function() {
		validaForm();

		if($('#data-payment').valid() === true) {
			sendPayment();
		}

	});
});

function sendPayment(code, amount)
{
	var dataRequest = JSON.stringify({
		code: $('#code').val(),
		amount: $('#amount').val(),
		reference: $('#reference').val(),
		desc: $('#desc').val()
	});

	$.ajax({
		method: 'post',
		url: baseURL + isoPais + '/payments',
		data: {method: 'PaymentSuppliers', model:'payments', dataRequest: dataRequest},
		beforeSend: function () {
			$('#data-payment').hide();
			$('#loading').show();
			$('#payment').prop('disabled', true);
		}
	}).done(function(response) {
		var title = response.title, message = response.msg, type;

		switch (response.code) {
			case 0:
				type = '';
				$('#code').val('');
				$('#amount').val('');
				$('#reference').val('');
				$('#desc').val('');
				break;
			case 2:
				type = 'serv';
				break;
			case 3:
				type = 'close';
				break;
		}
		notiSystem(title, message, type);
		$('#data-payment').show();
		$('#loading').hide();
		$('#payment').prop('disabled', false);
	});
}

