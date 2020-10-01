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
				var cardHolders = response.data.cardHoldersList;
				createTable(cardHolders);
				if (cardHolders.length == 0) {
					$('.download-icons').addClass('hide')
				} else {
					$('.download-icons').removeClass('hide')
				}
				form = $('#download-cardholders');
				form.html('')
				$.each(data, function (index, value) {
					if (index != 'screenSize') {
						form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
					}
				});
				insertFormInput(false);
				cardHolderBtn.html(btnText);
				$('#pre-loade-result').addClass('hide')
				$('.cardholders-result').removeClass('hide');
			})
		}
	});

	function createTable(cardHolders){
		resultscardHolders.DataTable({
			"ordering": false,
			"responsive": true,
			"pagingType": "full_numbers",
			"data": cardHolders,
			"columns": [
				{ data: 'cardHoldersId' },
				{ data: 'cardHoldersNum' },
				{ data: 'cardHoldersName' },
			],
			"columnDefs": [
				{
					"targets": 1,
					"visible": lang.CONF_CARD_NUMBER_COLUMN == "ON",
				},
			],
			"language": dataTableLang
		});
	};

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
