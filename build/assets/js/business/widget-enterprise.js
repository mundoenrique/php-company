'use strict'
$(function() {
	var enterpriseWidgetForm = $('#enterprise-widget-form');
	var WidgetSelcet = $('#enterprise-select');
	var WidgetSelcetP = $('#product-select');
	var enterpriseWidgetBtn = $('#enterprise-widget-btn');
	var formAction = enterpriseWidgetForm.attr('form-action');
	var prefix = getPropertyOfElement('prefix-prod', '#product-info');
	var currentIdFiscal = getPropertyOfElement('fiscal-reg', '#fiscal-reg');
	var enterpriseCode;
	var enterpriseGroup;
	var idFiscal;
	var enterpriseName;
	var productPrefix;
	var productName;
	var productBrand;
	var goToDetail = false;

	enterpriseWidgetForm.on('change', '#enterprise-select', function() {
		enterpriseCode = WidgetSelcet.find('option:selected').attr('code');
		enterpriseGroup = WidgetSelcet.find('option:selected').attr('group');
		idFiscal = WidgetSelcet.val()
		enterpriseName = WidgetSelcet.find('option:selected').text();

		if(formAction == 'productos') {
			enterpriseWidgetBtn
			.prop('disabled', false)
			.removeAttr('title');
		} else {
			WidgetSelcetP
			.prop('disabled', true)
			.find('option:selected').text(lang.GEN_WAIT_PRODUCTS);
			WidgetSelcetP.children()
			.not('option:selected')
			.remove()
			enterpriseWidgetBtn
			.prop('disabled', true);
			enterpriseWidgetBtn.attr('title', lang.GEN_SELECT_PRODUCTS);

			verb = 'POST'; who = 'Business'; where = 'getProducts';
			data = {
				enterpriseCode: enterpriseCode,
				enterpriseGroup: enterpriseGroup,
				idFiscal: idFiscal,
				enterpriseName: enterpriseName,
				select: true
			}
			callNovoCore(verb, who, where, data, function(response) {
				respProctList[response.code](response);
			})
		}
	});

	const respProctList = {
		0: function(response) {
			WidgetSelcetP.find('option:selected').text(lang.GEN_SELECT_PRODUCTS);
			goToDetail = true;
			$.each(response.data, function(index, prod) {
				if(prod.id == prefix && currentIdFiscal == idFiscal) {
					return;
				}
				WidgetSelcetP.append("<option value=" + prod.id + " brand=" + prod.brand + ">" + prod.desc + "</option>");
				// WidgetSelcetP.append(`<option value="${prod.id}" brand="${prod.brand}">${prod.desc}</option>`);
			});
			WidgetSelcetP.prop('disabled', false);
		}
	}

	enterpriseWidgetForm.on('change', '#product-select', function() {
		productPrefix = WidgetSelcetP.find('option:selected').val();
		productName = WidgetSelcetP.find('option:selected').text();
		productBrand = WidgetSelcetP.find('option:selected').attr('brand');
		enterpriseWidgetBtn
		.prop('disabled', false)
		.removeAttr('title');
	});

	enterpriseWidgetBtn.on('click', function(e) {
		searchEnterprise
		.off('click')
		.addClass('sb-disabled');
		e.preventDefault();
		insertFormInput(true, enterpriseWidgetForm);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		enterpriseWidgetForm.append("<input type='hidden' name='enterpriseCode' value=" + enterpriseCode + ">");
		enterpriseWidgetForm.append("<input type='hidden' name='enterpriseGroup' value=" + enterpriseGroup + ">");
		enterpriseWidgetForm.append("<input type='hidden' name='idFiscal' value=" + idFiscal + ">");
		enterpriseWidgetForm.append("<input type='hidden' name='enterpriseName' value=" + enterpriseName + ">");

		if(goToDetail) {
			enterpriseWidgetForm.append("<input type='hidden' name='productPrefix' value=" + productPrefix + ">");
			enterpriseWidgetForm.append("<input type='hidden' name='productName' value=" + productName + ">");
			enterpriseWidgetForm.append("<input type='hidden' name='productBrand' value=" + productBrand + ">");
			enterpriseWidgetForm.append("<input type='hidden' name='goToDetail' value='active'>");
		}

		enterpriseWidgetForm.submit();
	});
});
