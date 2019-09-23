$(function() {
	var code = $('#content-products').attr('code'), title, message, type,
		startDate = $('#first-date').val(), endDate = $('#last-date').val();
	switch(code){
		case '2':
			type = 'controls';
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

	controlsDate('first-date');
	controlsDate('last-date');

	$('#first-date').val(startDate);
	$('#last-date').val(endDate);


	$('input[type=checkbox]').on( "click", function() {
		if($(this).is(':checked')) {
			$(this).parent().find('label > span').html('&#x2714;');
			$(this).parent().siblings('.control-override-value').show();
			$(this).parent().siblings('.control-override').attr('show', 'y');
			$(this).parent().siblings('.control-override').find('span').text('-');
		} else {
			$(this).parent().find('label > span').html('&#x2716;');
			$(this).parent().siblings('.control-override-value').hide();
			$(this).parent().siblings('.control-override').attr('show', 'n');
			$(this).parent().siblings('.control-override').find('span').text('+');
		}

	});

	$('.control-override > span').on('click', function() {
		var show = $(this).parent().attr('show');
		if(show === 'n') {
			$(this).parent().siblings('.control-override-value').show();
			$(this).parent().attr('show', 'y');
			$(this).text('-')
		} else {
			$(this).parent().siblings('.control-override-value').hide();
			$(this).parent().attr('show', 'n');
			$(this).text('+');
		}

	});

	$('#loading').hide();
	$('#controls-list').show();
	$('#first-date, #last-date').prop('disabled', false);

	$('#update').on('click', function(){

		validaForm();

		if($('#contols-dates').valid() === true) {
			var firstDate, lastDate, dni, overrideCode, overrideCkec, controls = {}, plus,
				name, status, value, controlsValid = true;

			firstDate = $('#first-date').val();
			lastDate = $('#last-date').val();
			dni = $('#dni').val();
			$('input').removeClass('field-error');

			$('.visa-controls').each(function() {
				var overrides = [];
				overrideCkec = $(this).children('.control-check').find('input[type=checkbox]');
				overrideCode = $(this).children('.control-check').find('input').attr('id');
				controls[overrideCode] = {};
				plus = $(this).children('.control-check').find('input').attr('plus');
				status = $(this).children('.control-check').find('input').attr('status');
				if($(overrideCkec).is(':checked')) {
					switch(status) {
						case 'a':
							controls[overrideCode]['action'] = 'U';
							break;

						case 'i':
							controls[overrideCode]['action'] = 'A';
							break;
					}
					if(plus === 'y') {
						$(this).children('.control-override-value')
						       .find('input[name=' + overrideCode + ']').each(function(pos, element) {
							var override = {};
							value = $(element).val();
							name = $(element).attr('overrride');
							if((/^-?[0-9]+([.][0-9]{1,2})?$/).test(value)) {
								override['code'] = name;
								override['value'] = value;
								overrides.push(override);
								controlsValid = controlsValid === true ? true : controlsValid;
							} else {
								if($(this).val() !== '') {
									$(this).val('');
									$(this).attr('placeholder', 'Solo admite números');
								} else {
									$(this).attr('placeholder', 'Indique el monto');
								}
								$(this).addClass('field-error');
								controlsValid = false;
							}
						});
						controls[overrideCode]['overrides'] = overrides;

					}

				} else {
					controls[overrideCode]['action'] = 'D';
				}
			});

			if(!controlsValid) {
				var target = $('input.field-error:first');
				$('html, body').stop().animate({
					'scrollTop': target.offset().top - (parseInt($('#head').height()) + parseInt($('#nav-bar2').height()) + 100)
				}, 500, 'swing');
			} else {
				sendControls(firstDate, lastDate, dni, controls)
			}

		} else {
			$('html,body').animate({
				scrollTop: $('#content-products').offset().top
			},0);
		}

	});
});

function sendControls(firstDate, lastDate, dni, controls)
{
	var dataRequest = JSON.stringify({
		firstDate : formaterDate(firstDate),
		lastDate : formaterDate(lastDate),
		dni: dni,
		controls : controls

	});

	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	$.ajax({
		method: 'post',
		url: baseURL + isoPais + '/visa',
		data: {model: 'UpdateControls', dataRequest: dataRequest, ceo_name: ceo_cook},
		beforeSend: function () {
			$('#loading').show();
			$('#controls-list').hide();
			$('#update').prop('disabled', true);
		}
	}).done(function(response) {
		var title = response.title, message = response.msg, type;

		switch (response.code) {
			case 0:
				type = '';
				break;
			case 2:
				type = 'b';
				break;
			case 3:
				type = 'c';
				break;
		}
		notiSystem(title, message, type);
		$('#loading').hide();
		$('#controls-list').show();
		$('#update').prop('disabled', false);
	});
}

