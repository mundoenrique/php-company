'use strict'
$(function() {
	var enterpriseWidgetForm = $('#enterprise-widget-form');
	var WidgetSelcet = $('#enterprise-select');
	var enterpriseWidgetBtn = $('#enterprise-widget-btn')

	enterpriseWidgetForm.on('change', function() {
		enterpriseWidgetBtn
		.prop('disabled', false)
		.removeAttr('title')
	});

	enterpriseWidgetBtn.on('click', function(e) {
		e.preventDefault();
		insertFormInput(enterpriseWidgetForm);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		var enterpriseCode = WidgetSelcet.find('option:selected').attr('enterpriseCode')
		var enterpriseGroup = WidgetSelcet.find('option:selected').attr('enterpriseGroup')
		var idFiscal= WidgetSelcet.val()
		var EnterpriseName = WidgetSelcet.find('option:selected').text()
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseCode" value="${enterpriseCode}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseGroup" value="${enterpriseGroup}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="idFiscal" value="${idFiscal}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseName" value="${EnterpriseName}">`);
		enterpriseWidgetForm.submit();
	});
});
