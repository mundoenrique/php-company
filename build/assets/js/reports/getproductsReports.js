'use strict'
$(function () {
	$('.enterprise-getprod').on('change', function() {
		$('#productCode')
			.find(':first')
			.prop('selected', true)
			.siblings()
			.remove()

		verb = 'POST';
		who = 'Business';
		where = 'GetProducts';
		data = {
			idFiscal: $('#enterpriseCode').find('option:selected').attr('id-fiscal'),
			enterpriseCode: $('#enterpriseCode').val(),
			select: true
		}
		insertFormInput(true);

		callNovoCore(verb, who, where, data, function(response) {
			$.each(response.data, function(index, prod) {
				$('#productCode').append("<option value=" + prod.id + ">" + prod.desc + "</option>");
			});

			insertFormInput(false);
		});
	});
});
