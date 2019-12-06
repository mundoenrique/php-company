'use strict'
$(function() {
	var enterpriseWidgetForm = $('#enterprise-widget-form');
	var WidgetSelcet = $('#enterprise-select');
	var WidgetSelcetP = $('#product-select');
	var enterpriseWidgetBtn = $('#enterprise-widget-btn');
	var formAction = enterpriseWidgetForm.attr('form-action');
	var prefix = getPropertyOfElement('prefix-prod', '#detail-product');
	var enterpriseCode;
	var enterpriseGroup;
	var idFiscal;
	var enterpriseName;
	var productPrefix;
	var goToDetail = false;

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
			.not('option:selected')
			.remove()
			enterpriseWidgetBtn
			.prop('disabled', true);
			enterpriseWidgetBtn.attr('title', 'Selecciona un producto');

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
			goToDetail = true;
			$.each(response.data, function(index, prod) {
				if(prod.id == prefix) {
					return;
				}
				WidgetSelcetP.append(`<option value="${prod.id}">${prod.desc}</option>`);
			});
			WidgetSelcetP.prop('disabled', false);
		}
	}

	enterpriseWidgetForm.on('change', '#product-select', function() {
		productPrefix = WidgetSelcetP.val()
		enterpriseWidgetBtn
		.prop('disabled', false)
		.removeAttr('title');
	});

	enterpriseWidgetBtn.on('click', function(e) {
		e.preventDefault();
		insertFormInput(enterpriseWidgetForm);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseCode" value="${enterpriseCode}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseGroup" value="${enterpriseGroup}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="idFiscal" value="${idFiscal}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseName" value="${enterpriseName}">`);
		if(goToDetail) {
			enterpriseWidgetForm.append(`<input type="hidden" name="productPrefix" value="${productPrefix}">`);
			enterpriseWidgetForm.append(`<input type="hidden" name="goToDetail" value="active">`);
		}
		enterpriseWidgetForm.submit();
	});
});
