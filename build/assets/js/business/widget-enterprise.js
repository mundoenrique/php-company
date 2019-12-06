'use strict'
$(function() {
	var enterpriseWidgetForm = $('#enterprise-widget-form');
	var WidgetSelcet = $('#enterprise-select');
	var WidgetSelcetP = $('#product-select');
	var enterpriseWidgetBtn = $('#enterprise-widget-btn');
	var formAction = enterpriseWidgetForm.attr('form-action');
	var enterpriseCode;
	var enterpriseGroup;
	var idFiscal;
	var enterpriseName;

	enterpriseWidgetForm.on('change', '#enterprise-select', function() {
		enterpriseCode = WidgetSelcet.find('option:selected').attr('code')
		enterpriseGroup = WidgetSelcet.find('option:selected').attr('group')
		idFiscal = WidgetSelcet.val()
		enterpriseName = WidgetSelcet.find('option:selected').text()
		if(formAction == 'productos') {
			enterpriseWidgetBtn
			.prop('disabled', false)
			.removeAttr('title');
		} else {
			WidgetSelcetP
			.prop('disabled', true)
			.find('option:selected').text('Esperando productos...')
			WidgetSelcetP.children()
			.not(':first-child')
			.remove()

			verb = 'POST'; who = 'Business'; where = 'getProducts';
			data = {
				enterpriseCode: enterpriseCode,
				enterpriseGroup: enterpriseGroup,
				idFiscal: idFiscal,
				enterpriseName: enterpriseName,
				select: true
			}
			callNovoCore(verb, who, where, data, function(response) {
				resproctList[response.code](response);
			})
		}
	});

	const resproctList = {
		0: function(response) {
			WidgetSelcetP.find('option:selected').text('Selecciona un producto');
			$.each(response.data, function(index, prod){
				WidgetSelcetP.append(`<option value="${prod.id}">${prod.desc}</option>`)
			});
			WidgetSelcetP.prop('disabled', false);

		}
	}

	enterpriseWidgetBtn.on('click', function(e) {
		e.preventDefault();
		insertFormInput(enterpriseWidgetForm);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseCode" value="${enterpriseCode}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseGroup" value="${enterpriseGroup}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="idFiscal" value="${idFiscal}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseName" value="${enterpriseName}">`);
		enterpriseWidgetForm.submit();
	});
});
