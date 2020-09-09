'use strict'
$(function () {
	var enterpriseGetprod = $('.enterprise-getprod');
	var productSelectCode = $('#productCode');

	enterpriseGetprod.on('change', function() {
		productSelectCode
		.find(':first')
		.prop('selected', true)
		.siblings()
		.remove()
		data = {
			idFiscal: $('#enterpriseCode').find('option:selected').attr('id-fiscal'),
			enterpriseCode: $('#enterpriseCode').val(),
			select: true
		}
		insertFormInput(true);
		verb = 'POST'; who = 'Business'; where = 'GetProducts';

		callNovoCore(verb, who, where, data, function(response) {

			$.each(response.data, function(index, prod) {
				productSelectCode.append("<option value=" + prod.id + ">" + prod.desc + "</option>");
			});
			insertFormInput(false);
		})

	})
});
