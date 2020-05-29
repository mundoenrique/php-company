'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var resultscardHolders = $('#resultscardHolders');
	var cardHolderBtn = $('#card-holder-btn');
	var downLoad = $('.download');

	cardHolderBtn.on('click', function (e) {
		form = $('#card-holder-form');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('.cardholders-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide');
			resultscardHolders.dataTable().fnClearTable();
			resultscardHolders.dataTable().fnDestroy();
			verb = "POST"; who = 'Reports'; where = 'CardHolders';
			callNovoCore(verb, who, where, data, function (response) {
				var table = resultscardHolders.DataTable({
					"ordering": false,
					"responsive": true,
					"pagingType": "full_numbers",
					"language": dataTableLang
				});

				if (response.code == 0) {
					if (response.data.length == 0) {
						$('.download-icons').addClass('hide')
					} else {
						$('.download-icons').removeClass('hide')
					}

					$.each(response.data, function (index, value) {
						table.row.add([
							value.cardHoldersId,
							value.cardHoldersName,
						]).draw()
					});
					form = $('#download-cardholders');
					form.html('')
					$.each(data, function (index, value) {
						if (index != 'screenSize') {
							form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
						}
					});
				}

				insertFormInput(false);
				cardHolderBtn.html(btnText);
				$('#pre-loade-result').addClass('hide')
				$('.cardholders-result').removeClass('hide');
			})
		}
	});

	downLoad.on('click', 'button', function (e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');

		var enterpriseName = $('option:selected', "#enterpriseCode").text().trim();
		var acrif = $('option:selected', "#enterpriseCode").val().trim();
		var productName = $('option:selected', "#productCode").text().trim();
		var productLots = $('option:selected', "#productCode").val().trim();

		form = $('#download-cardholders');
		form.append('<input type="hidden" name="type" value="' + action + '"></input>');
		form.append('<input type="hidden" name="who" value="DownloadFiles"></input>');
		form.append('<input type="hidden" name="where" value="CardHoldersReport"></input>');
		form.append('<input type="hidden" name="enterpriseName" value="' + enterpriseName + '"></input>');
		form.append('<input type="hidden" name="acrif" value="' + acrif + '"></input>');
		form.append('<input type="hidden" name="productName" value="' + productName + '"></input>');
		form.append('<input type="hidden" name="productLots" value="' + productLots + '"></input>');
		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.GEN_TIME_DOWNLOAD_FILE);
	});
});
