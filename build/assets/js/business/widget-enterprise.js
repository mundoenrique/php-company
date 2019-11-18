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
		$(this).css('width', '111.1px');
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		$(':button').prop('disabled', true);
		var EnterpriseName = WidgetSelcet.find('option:selected').text()
		var idFiscal= WidgetSelcet.val()
		enterpriseWidgetForm.append(`<input type="hidden" name="idFiscal" value="${idFiscal}">`);
		enterpriseWidgetForm.append(`<input type="hidden" name="enterpriseName" value="${EnterpriseName}">`);
		enterpriseWidgetForm.submit();
	});
});
