'use strict'
$(function () {
	$('.enterprise-getprod').on('change', function() {
		$('#productCode')
			.find(':first')
			.prop('selected', true)
			.siblings()
			.remove()

		who = 'Business';
		where = 'GetProducts';
		data = {
			idFiscal: $('#enterpriseCode').find('option:selected').attr('id-fiscal'),
			enterpriseCode: $('#enterpriseCode').val(),
			select: true
		}
		insertFormInput(true);

		var product = document.getElementById('productCode')

		callNovoCore(who, where, data, function(response) {
			$.each(response.data, function(index, prod) {
				var opt = document.createElement('option');
				opt.value = prod.id;
				opt.text = prod.desc;
				opt.setAttribute('doc', prod.desc);
				product.options.add(opt);
			});

			insertFormInput(false);
		});
	});
});
